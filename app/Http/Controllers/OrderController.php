<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Repository\ProductRepository;
use App\Http\Repository\StoreRepository;
use App\Models\Order;
use App\Models\OrderItems;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'store_id' => 'required|int',
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'order_items' => 'required',
            'booking_date' => 'required',
            'booking_time' => 'required',
            'customer_total' => 'required',
            'payment_method' => 'required',
            'payment_image' => 'required',
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        /* Get Store */
        $store = StoreRepository::find($request->store_id);
        if (!$store) {
            return Response::error('Store not found', 404);
        }

        DB::beginTransaction();
        try {
            /* Upload Image */
            $image = $request->payment_image;
            if (str_contains($image, 'base64,')) {
                $image = explode('base64,', $image)[1];
            }

            $image = base64_decode($image);
            $finfo = finfo_open();
            $mimeType = finfo_buffer($finfo, $image, FILEINFO_MIME_TYPE);

            /* Check Image Extension */
            $allowed = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!in_array($mimeType, $allowed)) {
                return Response::error('Format tidak valid');
            }

            $fileName = Str::random(20) . '.png';
            file_put_contents('uploads/' . $fileName, $image);

            /* Create Order */
            $orderId = strtoupper(Str::random(8));
            $data = [
                'store_id' => $store->id,
                'order_id' => $orderId,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date . ' ' . $request->booking_time,
                'customer_total' => $request->customer_total,
                'note' => $request->note,
                'payment_method' => $request->payment_method,
                'payment_image' => $fileName,
                'payment_total' => 0,
                'total' => 0,
                'device_id' => $request->device_id
            ];

            if (Auth::guard('api')->check()) {
                $data['user_id'] = Auth::guard('api')->id();
            }

            $order = Order::create($data);

            /* Create Order Items */
            $orderItems = json_decode($request->order_items, true);
            $orderItemsData = [];
            $total = 0;

            foreach ($orderItems as $item) {
                if (!isset($item['product_id']) || !isset($item['qty'])) {
                    DB::rollBack();
                    return Response::error('Cannot process request', 422);
                }

                /* Get Product */
                $product = ProductRepository::find($item['product_id']);
                if (!$product) {
                    DB::rollBack();
                    return Response::error('Cannot process request', 422);
                }

                $orderItemsData[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['qty']
                ];

                $total += intval($product->price) * intval($item['qty']);
            }

            if (count($orderItemsData) <= 0) {
                DB::rollBack();
                return Response::error('Cannot process request', 422);
            }

            OrderItems::insert($orderItemsData);

            /* Update Order */
            $order->update([
                'total' => $total,
                'payment_total' => $request->payment_method === 'custom' ? $request->nominal_dp : ($request->payment_method === 'dp' ? $total / 2 : $total)
            ]);

            DB::commit();

            return Response::successWithData([
                'order_id' => $orderId
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('failed to checkout', $e);
            return Response::error();
        }
    }

    public function confirm(Order $order)
    {
        if (!Auth::check()) {
            return Response::error('Unauthorized', 401);
        }

        try {
            $order->update([
                'status' => 'confirmed'
            ]);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to confirm order', $e);
            return Response::error();
        }
    }

    public function detail(Request $request, Order $order)
    {
        /* Check Permission */
        // if (!Auth::check() || Auth::user()->role !== 'admin') {
        //     if (Auth::check() && $order->user_id !== Auth::id()) {
        //         return Response::error('Unauthorized', 401);
        //     } else {
        //         // if (!$request->device_id) {
        //         //     return Response::error('Unauthorized', 401);
        //         // }

        //         // if ($order->device_id !== $request->device_id) {
        //         //     return Response::error('Unauthorized', 401);
        //         // }
        //     }
        // }

        $order->load('order_items');

        return Response::successWithData(
            $order
        );
    }

    public function history()
    {
        $orders = Order::with('order_items')
            ->whereDate('created_at', Carbon::now());

        if (Auth::guard('api')->check()) {
            $orders->where('user_id', Auth::guard('api')->id());
        }

        $orders = $orders->latest()
            ->get();

        return Response::successWithData(
            $orders
        );
    }

    public function datatables(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $limit = (int) $request->query('limit', 10);
        $search = trim((string) $request->query('search', ''));

        $query = Order::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%");
                $q->orWhere('customer_name', 'like', "%{$search}%");
                $q->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $data = $query
            ->orderByRaw("
        CASE 
            WHEN status = 'pending' THEN 1
            WHEN status = 'confirmed' THEN 2
            WHEN status = 'completed' THEN 3
            ELSE 4
        END
    ")
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        return Response::successWithData([
            'orders' => collect($data->items())->map(function ($item) {
                $array = $item->toArray();

                if ($item->created_at) {
                    Carbon::setLocale('id');
                    $array['booking_date'] = $item->created_at->translatedFormat('l, d F Y');
                    $array['booking_time'] = $item->created_at->format('H:i');
                }

                return $array;
            })->values(),
            'data_total' => $data->total()
        ]);
    }

    public function orderReportDay()
    {
        return Response::successWithData(
            Order::whereDate('created_at', Carbon::now())->count()
        );
    }

    public function pendingReportTotal()
    {
        return Response::successWithData(
            Order::where('status', 'pending')->count()
        );
    }

    public function confirmedReportTotal()
    {
        return Response::successWithData(
            Order::where('status', 'confirmed')->count()
        );
    }
}
