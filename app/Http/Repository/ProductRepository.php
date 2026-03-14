<?php

namespace App\Http\Repository;

use App\Models\PacketProducts;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ProductRepository
{
    public static function find(int $id)
    {
        return Cache::remember('product_' . $id, Carbon::now()->addHours(1), function () use ($id) {
            return Product::find($id);
        });
    }

    public static function getByPacket(int $packetId)
    {
        return Cache::remember('products_packet_' . $packetId, Carbon::now()->addHours(1), function () use ($packetId) {
            $packetProducts = PacketProducts::where('packet_id', $packetId)
                ->get()
                ->toArray();

            return Product::whereIn('id', array_column($packetProducts, 'product_id'))
                ->orderBy('price', 'asc')
                ->get();
        });
    }
}
