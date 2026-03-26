<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
            'category_id' => 'required|int',
            'name' => 'required',
            'order_number' => 'required|int'
        ], [
            'category_id' => [
                'required' => 'Kategori wajib diisi'
            ],
            'name' => [
                'required' => 'Nama paket wajib diisi'
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
            'store_id' => Auth::user()->store_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'order_number' => $request->order_number,
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        try {
            Order::create($data);

            return Response::success();
        } catch (\Throwable $e) {
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
