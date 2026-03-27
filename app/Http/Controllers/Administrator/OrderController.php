<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('order', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = Order::with('store');

            if (Auth::user()->role->level > 1) {
                $data->where('store_id', Auth::user()->store_id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->addColumn('booking_date', function ($row) {
                    return Carbon::parse($row->booking_date)->format('j F Y');
                })
                ->addColumn('booking_time', function ($row) {
                    return Carbon::parse($row->booking_time)->format('H:i');
                })
                ->make(true);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function index()
    {
        /* Check Access */
        if (!check_user_access('order', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.order.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('order', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.order.create.index');
    }

    public function show(Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        $order->load(['store', 'order_items']);

        return view('pages.order.detail.index', compact(['order']));
    }

    public function edit(Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        $order->load(['store', 'order_items']);

        return view('pages.order.edit.index', compact(['order']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('order', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'booking_date' => 'required',
            'customer_total' => 'required|int',
            'order_items' => 'required',
            'payment_method' => 'required',
            'payment_total' => 'required|int',
            'image' => 'required|image'
        ], [
            'customer_name' => [
                'required' => 'Nama pelanggan wajib diisi'
            ],
            'customer_phone' => [
                'required' => 'WhatsApp wajib diisi'
            ],
            'booking_date' => [
                'required' => 'Tanggal wajib diisi',
            ],
            'customer_total' => [
                'required' => 'Jumlah Tamu wajib diisi',
                'int' => 'Jumlah tamu harus berupa angka'
            ],
            'order_items' => [
                'required' => 'Harap memilih setidaknya satu menu'
            ],
            'payment_method' => [
                'required' => 'Harap memilih metode pembayaran'
            ],
            'payment_total' => [
                'required' => 'Jumlah dibayar wajib diisi',
                'int' => 'Jumlah dibayar harus berupa angka'
            ],
            'image' => [
                'required' => 'Harap mengupload gambar',
                'image' => 'Harap mengupload gambar'
            ]
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        DB::beginTransaction();
        try {
            /* Upload Image */
            $image = $request->file('image');
            $filename = $image->hashName();
            $image->move('uploads', $filename);

            /* Create Order */
            $orderId = strtoupper(Str::random(8));
            $data = [
                'store_id' => Auth::user()->store_id,
                'order_id' => $orderId,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'customer_total' => $request->customer_total,
                'customer_seat' => $request->customer_seat,
                'note' => $request->note,
                'payment_method' => $request->payment_method === 'custom' ? 'dp' : $request->payment_method,
                'payment_image' => $filename,
                'payment_total' => $request->integer('payment_total') ?? 0,
                'total' => 0,
                'device_id' => $request->device_id
            ];

            $order = Order::create($data);

            /* Create Order Items */
            $orderItemsData = [];
            $total = 0;

            foreach ($request->post('order_items') as $productId) {
                /* Get Product */
                $product = Product::find($productId);
                if (!$product) {
                    DB::rollBack();
                    return Response::error('Cannot process request', 422);
                }

                $qty = intval($request->post('qty')[$productId]);
                $orderItemsData[] = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty
                ];

                $total += intval($product->price) * $qty;
            }

            if (count($orderItemsData) <= 0) {
                DB::rollBack();
                return Response::error('Cannot process request', 422);
            }

            OrderItems::insert($orderItemsData);

            /* Update Order */
            $order->update([
                'total' => $total,
                'payment_total' => $request->payment_method === 'custom' ? $request->integer('payment_total') : ($request->payment_method === 'dp' ? $total / 2 : $total)
            ]);

            DB::commit();

            return Response::success();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('failed to store order', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($order->store_id !== Auth::user()->store_id) {
            return Response::error('Unauthorized', 401);
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'category_id' => 'required|int',
            'name' => 'required',
            'image' => 'nullable|image',
            'order_number' => 'required|int'
        ], [
            'category_id' => [
                'required' => 'Kategori wajib diisi'
            ],
            'name' => [
                'required' => 'Nama paket wajib diisi'
            ],
            'image' => [
                'image' => 'Harap mengupload gambar'
            ],
            'order_number' => [
                'required' => 'Urutan wajib diisi',
                'int' => 'Urutan harus berupa angka',
            ]
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'order_number' => $request->order_number,
            'updated_by' => Auth::id()
        ];

        /* Update Data */
        try {
            $order->update($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update order', $e);
            return Response::error();
        }
    }

    public function updatePaymentTotal(Request $request, Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($order->store_id !== Auth::user()->store_id) {
            return Response::error('Unauthorized', 401);
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'payment_total' => 'required|int',
        ], [
            'payment_total' => [
                'required' => 'Jumlah dibayar wajib diisi',
                'int' => 'Jumlah dibayar harus berupa angka',
            ]
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        try {
            $order->update([
                'payment_total' => $request->integer('payment_total')
            ]);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update payment total', $e);
            return Response::error();
        }
    }

    public function confirmOrder(Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($order->store_id !== Auth::user()->store_id) {
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

    public function destroy(Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'delete')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($order->store_id !== Auth::user()->store_id) {
            return Response::error('Unauthorized', 401);
        }

        try {
            $order->delete();

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete order', $e);
            return Response::error();
        }
    }
}
