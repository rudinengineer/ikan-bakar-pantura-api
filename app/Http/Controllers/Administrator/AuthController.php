<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.login');
    }

    public function store(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'remember_me' => 'nullable|boolean',
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $rememberMe = $request->boolean('remember_me');

        try {
            /* Find User By Username */
            $user = User::where('username', $request->username)->first();
            if (!$user) {
                return Response::error('Username atau password salah');
            }

            /* Reset Login Attempt */
            if ($user->locked_at) {
                $now = Carbon::now();
                $locked_expired_at = Carbon::parse($user->locked_at)->addMinutes(15);

                if ($now > $locked_expired_at) {
                    /* Reset Login Attempt */
                    $user->update([
                        'login_attempt' => 5,
                        'locked_at' => null,
                    ]);
                }
            }

            if ($user->login_attempt <= 0) {
                /* Lock User */
                $user->update([
                    'locked_at' => Carbon::now(),
                ]);

                return Response::error('Terlalu banyak permintaan login. Harap menunggu 15 menit!', 401);
            }

            /* Validate Password */
            if (!password_verify($request->password, $user->password)) {
                /* Update Login Attempt */
                $user->decrement('login_attempt', 1);

                return Response::error('Username atau password salah', 401);
            }

            /* Check User is Active */
            if (!$user->is_active) {
                return Response::error('Akun tidak aktif. Hubungi admin untuk mengaktifkan akun!', 401);
            }

            /* Set Session */
            Auth::attempt([
                'username' => $user->username,
                'password' => $request->password
            ], $rememberMe);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to login', $e);
            return Response::error();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect(route('login'));
    }
}
