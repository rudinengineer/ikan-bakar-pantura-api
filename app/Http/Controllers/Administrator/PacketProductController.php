<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Packet;
use App\Models\PacketProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PacketProductController extends Controller
{
    public function datatables(Packet $packet)
    {
        /* Check Access */
        if (!check_user_access('packet', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = PacketProducts::with('product')
                ->where('packet_id', $packet->id);

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function index(Packet $packet)
    {
        /* Check Access */
        if (!check_user_access('packet', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.packet-product.index', compact('packet'));
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('packet', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.packet-product.create');
    }

    public function store(Request $request, Packet $packet)
    {
        /* Check Access */
        if (!check_user_access('packet', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'product_items' => 'required',
        ], [
            'product_items' => [
                'required' => 'Harap memilih menu'
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

        $data = [];
        foreach ($request->post('product_items') as $row) {
            $findData = PacketProducts::where('packet_id', $packet->id)->where('product_id', $row)->first();

            if (!$findData) {
                $data[] = [
                    'packet_id' => $packet->id,
                    'product_id' => $row,
                ];
            }
        }

        /* Insert Data */
        try {
            PacketProducts::insert($data);
            Cache::forget('products_packet_' . $packet->id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to store packet product', $e);
            return Response::error();
        }
    }

    public function destroy(PacketProducts $packetproducts)
    {
        /* Check Access */
        if (!check_user_access('packet', 'delete')) {
            return redirect(route('notfound'));
        }

        try {
            $packetproducts->delete();
            Cache::forget('products_packet_' . $packetproducts->packet_id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete packet product', $e);
            return Response::error();
        }
    }
}
