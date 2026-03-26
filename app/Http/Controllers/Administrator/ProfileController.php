<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        return view('pages.profile.index', compact(['user']));
    }

    public function update(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'old_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8'],
            'confirm_password' => ['required', 'same:password'],
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
            'password' => bcrypt($request->password)
        ];

        try {
            User::find(Auth::id())->update($data);
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update profile', $e);
            return Response::error();
        }
    }
}
