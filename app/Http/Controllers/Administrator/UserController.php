<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('user-management', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = User::with(['role', 'store']);

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function index()
    {
        /* Check Access */
        if (!check_user_access('user-management', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.user-management.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('user-management', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.user-management.create.index');
    }

    public function edit(User $user)
    {
        /* Check Access */
        if (!check_user_access('user-management', 'update')) {
            return redirect(route('notfound'));
        }

        $user->load(['role', 'store']);

        return view('pages.user-management.edit.index', compact(['user']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('user-management', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'store_id' => 'required|int',
            'role_id' => 'required|int',
            'name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username',
            'email' => 'required|email',
            'phone' => 'required|int',
            'password' => 'required'
        ], [
            'store_id' => [
                'required' => 'Harap memilih cabang',
                'int' => 'Cabang harus berupa angka'
            ],
            'role_id' => [
                'required' => 'Harap memilih role',
                'int' => 'Role harus berupa angka'
            ],
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'username' => [
                'required' => 'Username wajib diisi',
                'alpha_dash' => 'Format username tidak valid',
                'unique' => 'Username sudah terpakai'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'email' => 'Format email tidak valid'
            ],
            'phone' => [
                'required' => 'No. Telp wajib diisi',
                'int' => 'No. Telp harus berupa angka'
            ],
            'password' => [
                'required' => 'Password wajib diisi'
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
            'store_id' => $request->integer('store_id'),
            'role_id' => $request->integer('role_id'),
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        try {
            User::create($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to store user', $e);
            return Response::error();
        }
    }

    public function update(Request $request, User $user)
    {
        /* Check Access */
        if (!check_user_access('user-management', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'store_id' => 'required|int',
            'role_id' => 'required|int',
            'name' => 'required',
            'username' => 'required|alpha_dash|unique:users,username,' . $user->id,
            'email' => 'required|email',
            'phone' => 'required|int',
        ], [
            'store_id' => [
                'required' => 'Harap memilih cabang',
                'int' => 'Cabang harus berupa angka'
            ],
            'role_id' => [
                'required' => 'Harap memilih role',
                'int' => 'Role harus berupa angka'
            ],
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'username' => [
                'required' => 'Username wajib diisi',
                'alpha_dash' => 'Format username tidak valid',
                'unique' => 'Username sudah terpakai'
            ],
            'email' => [
                'required' => 'Email wajib diisi',
                'email' => 'Format email tidak valid'
            ],
            'phone' => [
                'required' => 'No. Telp wajib diisi',
                'int' => 'No. Telp harus berupa angka'
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
            'store_id' => $request->integer('store_id'),
            'role_id' => $request->integer('role_id'),
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => $request->boolean('is_active'),
            'updated_by' => Auth::id()
        ];

        if ($request->pasword) {
            $data['password'] = bcrypt($request->password);
        }

        /* Update Data */
        try {
            $user->update($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update user', $e);
            return Response::error();
        }
    }

    public function destroy(User $user)
    {
        /* Check Access */
        if (!check_user_access('user-management', 'delete')) {
            return redirect(route('notfound'));
        }

        try {
            $user->delete();
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete user', $e);
            return Response::error();
        }
    }
}
