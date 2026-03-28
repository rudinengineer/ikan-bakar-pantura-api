<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Resources\UserResource;
use App\Models\Customer;
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
            'store_id' => 'required|int',
            'name' => 'required',
            'phone' => 'required|unique:customers',
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
            $user = Customer::create([
                'store_id' => $request->store_id,
                'name' => $request->name,
                'username' => Str::slug($request->name, '_') . random_int(1000, 9999),
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                // 'role' => 'user'
            ]);

            /* Generate Access Token */
            $token = Auth::guard('api')->login($user);

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

        $user = Customer::where('phone', $request->phone)
            ->first();

        if (!$user) {
            return Response::error('Unauthorized', 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return Response::error('Unauthorized', 401);
        }

        if (!$token = Auth::guard('api')->login($user)) {
            return Response::error('Unauthorized', 401);
        }

        return Response::successWithData([
            'access_token' => $token,
            'user' => new UserResource($user)
        ]);
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'logout success']);
    }
}
