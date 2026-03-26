<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Response;
use App\Http\Repository\CategoryRepository;
use App\Http\Resources\PacketResource;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PacketController extends Controller
{
    public function getByCategory(Request $request, $slug)
    {
        $category = CategoryRepository::findBySlug($slug);
        if (!$category) {
            return Response::error('Category not found', 404);
        }

        if ($request->keyword) {
            $packets = Packet::with('packet_products.product')
                ->where('category_id', $category->id)
                ->whereHas('packet_products.product', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->keyword . '%');
                })
                ->orderBy('order_number', 'asc')
                ->get();
        } else {
            $packets = Cache::rememberForever('packets_category_' . $category->id, function () use ($category) {
                return Packet::where('category_id', $category->id)
                    ->orderBy('order_number', 'asc')
                    ->get();
            });
        }

        return Response::successWithData(
            PacketResource::collection($packets)
        );
    }
}
