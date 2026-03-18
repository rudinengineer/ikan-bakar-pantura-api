<?php

namespace App\Http\Repository;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingRepository
{
    public static function get()
    {
        return Cache::rememberForever('setting', function () {
            $setting = Setting::first();

            if (!$setting) {
                $setting = Setting::create([
                    'store_id' => config('app.store_id'),
                    'bank' => ''
                ]);
            }

            return $setting;
        });
    }
}
