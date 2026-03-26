<?php

namespace App\Http\Repository;

use App\Models\Store;
use Illuminate\Support\Facades\Cache;

class StoreRepository
{
    public static function getAllStore()
    {
        return Cache::rememberForever('stores', function () {
            return Store::where('is_active', true)->get();
        });
    }

    public static function find(int $id)
    {
        return Cache::rememberForever('store-' . $id, function () use ($id) {
            return Store::find($id);
        });
    }
}
