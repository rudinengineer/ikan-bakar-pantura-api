<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function reportTotal()
    {
        return Response::successWithData([
            User::where('role', 'user')->count()
        ]);
    }

    public function profile()
    {
        return Response::successWithData(
            Auth::user()
        );
    }

    public function update(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'phone' => [
                'required' => 'nomor whatsapp wajib diisi'
            ],
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $data = [
            'name' => $request->name,
            'username' => Str::slug($request->name, '_') . random_int(1000, 9999),
            'phone' => $request->phone,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        try {
            User::where('id', Auth::id())->update($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update profile', $e);
            return Response::error();
        }
    }
}
