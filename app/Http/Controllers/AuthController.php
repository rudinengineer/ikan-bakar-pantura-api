<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
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
            /* Create User */
            $user = User::create([
                'store_id' => config('app.store_id'),
                'name' => $request->name,
                'username' => Str::slug($request->name, '_') . random_int(1000, 9999),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'user'
            ]);

            /* Generate Access Token */
            $token = Auth::attempt(
                $request->only('phone', 'password')
            );

            return Response::successWithData([
                'access_token' => $token,
                'user' => new UserResource($user)
            ]);
        } catch (\Throwable $e) {
            Log::error('failed to register', $e);
            return Response::error();
        }
    }

    public function login(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $credentials = $request->only('phone', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return Response::error('Unauthorized', 401);
        }

        $user = User::where('phone', $request->phone)
            ->first();

        return Response::successWithData([
            'access_token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'logout success']);
    }
}
