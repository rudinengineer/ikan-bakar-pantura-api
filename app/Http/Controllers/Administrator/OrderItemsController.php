<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OrderItemsController extends Controller
{
    public function datatables(Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = OrderItems::with('product')
                ->where('order_id', $order->id);

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function index(Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.order-items.index', compact('order'));
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('order', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.order-items.create.index');
    }

    public function edit(OrderItems $orderitems)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.order-items.edit.index', compact(['orderitems']));
    }

    public function store(Request $request, Order $order)
    {
        /* Check Access */
        if (!check_user_access('order', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'product_id' => 'required|int',
            'qty' => 'required|int',
        ], [
            'product_id' => [
                'required' => 'Harap memilih menu',
                'int' => 'Menu harus berupa angka'
            ],
            'qty' => [
                'required' => 'QTY wajib diisi',
                'int' => 'QTY harus berupa angka'
            ],
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        /* Insert Data */
        DB::beginTransaction();
        try {
            $product = Product::find($request->product_id);

            /* Update QTY if Data Exists */
            $orderItems = OrderItems::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->first();

            if ($orderItems) {
                $orderItems->increment('quantity', 1);

                /* Update Order Total */
                $order->increment('total', intval($product->price) * intval($orderItems->quantity));

                DB::commit();
                return Response::success();
            }

            $data = [
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->integer('qty'),
                'product_name' => $product->name,
                'price' => $product->price,
                'created_by' => Auth::id()
            ];

            OrderItems::create($data);

            /* Update Order Total */
            $order->increment('total', intval($product->price) * $request->integer('qty'));

            DB::commit();

            return Response::success();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('failed to store order items', $e);
            return Response::error();
        }
    }

    public function update(Request $request, OrderItems $orderitems)
    {
        /* Check Access */
        if (!check_user_access('order', 'update')) {
            return redirect(route('notfound'));
        }

        $orderitems->load('order');

        /* Validate Access */
        if (
            $orderitems->order->store_id !== Auth::user()->store_id &&
            Auth::user()->role->level > 1
        ) {
            return Response::error('Unauthorized', 401);
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'qty' => 'required|int',
        ], [
            'qty' => [
                'required' => 'QTY wajib diisi',
                'int' => 'QTY harus berupa angka'
            ],
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
            'quantity' => $request->integer('qty'),
            'updated_by' => Auth::id()
        ];

        /* Update Data */
        DB::beginTransaction();
        try {
            $orderitems->update($data);

            /* Get All Order Items */
            $items = OrderItems::with('product')
                ->where('order_id', $orderitems->order_id)
                ->get();

            $total = 0;
            foreach ($items as $row) {
                $total += intval($row->product->price) * intval($row->quantity);
            }

            /* Update Order */
            Order::where('id', $orderitems->order_id)
                ->update([
                    'total' => $total
                ]);

            DB::commit();

            return Response::success();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('failed to update order items', $e);
            return Response::error();
        }
    }

    public function destroy(OrderItems $orderitems)
    {
        /* Check Access */
        if (!check_user_access('order', 'delete')) {
            return redirect(route('notfound'));
        }

        $orderitems->load('order');

        /* Validate Access */
        if (
            $orderitems->order->store_id !== Auth::user()->store_id &&
            Auth::user()->role->level > 1
        ) {
            return Response::error('Unauthorized', 401);
        }

        DB::beginTransaction();
        try {
            /* Update Order */
            Order::where('id', $orderitems->order_id)
                ->decrement('total', intval($orderitems->product->price) * intval($orderitems->quantity));

            /* Delete Order Items */
            $orderitems->delete();

            DB::commit();
            return Response::success();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('failed to delete order items', $e);
            return Response::error();
        }
    }
}
