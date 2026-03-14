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
                    'bank' => ''
                ]);
            }

            return $setting;
        });
    }
}
