<?php

namespace App\Http\Repository;

use App\Models\Packet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PacketRepository
{
    public static function findBySlug(string $slug)
    {
        return Cache::remember('packet_slug_' . $slug, Carbon::now()->addHours(1), function () use ($slug) {
            return Packet::where('slug', $slug)->first();
        });
    }
}
