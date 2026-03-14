<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Packet;
use App\Models\PacketProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class PacketProductController extends Controller
{
    public function datatables(Request $request, Packet $packet)
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

        $query = PacketProducts::with('product')
            ->where('packet_id', $packet->id);

        $data = $query
            ->latest('id')
            ->paginate($limit)
            ->withQueryString();

        return Response::successWithData([
            'products' => $data->items(),
            'data_total' => $data->total()
        ]);
    }

    public function store(Request $request, Packet $packet)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'product_id' => 'required',
        ], [
            'product_id' => [
                'required' => 'Harap memilih menu',
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

        try {
            $products = [];
            foreach ($request->product_id as $product_id) {
                $products[] = [
                    'packet_id' => $packet->id,
                    'product_id' => $product_id
                ];
            }

            PacketProducts::insert($products);
            Cache::forget('products_packet_' . $packet->id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to save packet product', $e);
            return Response::error();
        }
    }

    public function delete(PacketProducts $packetproducts)
    {
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
