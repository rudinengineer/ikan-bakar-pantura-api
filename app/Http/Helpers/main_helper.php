<?php

use App\Models\UserAccess;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

if (!function_exists('check_user_access')) {
    function check_user_access(string $accessLink, string $accessType): bool
    {
        /* Check if Superadmin */
        if (Auth::user()->role_id === 1) {
            return true;
        }

        /* Get All User Access */
        $userAccess = Cache::remember('user_access_' . Auth::user()->role_id, Carbon::now()->addHours(12), function () {
            return UserAccess::with('user_access_item')
                ->where('role_id', Auth::user()->role_id)
                ->get()
                ->toArray();
        });

        /* Check User Access */
        $status = false;
        foreach ($userAccess as $row) {
            if (
                $row['user_access_item']['access_link'] === $accessLink &&
                $row['access_' . $accessType]
            ) {
                $status = true;
                break;
            }
        }

        return $status;
    }
}
