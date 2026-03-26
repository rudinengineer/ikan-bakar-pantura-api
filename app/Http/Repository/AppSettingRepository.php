<?php

namespace App\Http\Repository;

use App\Models\AppSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AppSettingRepository
{
    public static function get()
    {
        return Cache::remember('app_setting', Carbon::now()->addHours(12), function () {
            $setting = AppSetting::first();

            if (!$setting) {
                AppSetting::create([
                    'app_name' => 'Dashboard'
                ]);

                $setting = AppSetting::first();
            }

            return $setting;
        });
    }
}
