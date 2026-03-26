<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('role', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = Role::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function select2(Request $request)
    {
        $keyword = $request->q;

        $data = Role::where('name', 'like', '%' . $keyword . '%')
            ->limit(50)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    public function index()
    {
        /* Check Access */
        if (!check_user_access('role', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.role.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('role', 'create')) {
            return redirect(route('notfound'));
        }

        $latest_role = Role::latest()->first();

        $data = [
            'current_role_level' => intval($latest_role->level) + 1
        ];

        return view('pages.role.create', $data);
    }

    public function edit(Role $role)
    {
        /* Check Access */
        if (!check_user_access('role', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.role.edit', compact(['role']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('role', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'level' => 'required|int',
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
            'level' => $request->integer('level'),
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        try {
            Role::create($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to store role', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Role $role)
    {
        /* Check Access */
        if (!check_user_access('role', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'level' => 'required|int',
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
            'level' => $request->integer('level'),
            'updated_by' => Auth::id()
        ];

        /* Update Data */
        try {
            $role->update($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update role', $e);
            return Response::error();
        }
    }

    public function destroy(Role $role)
    {
        /* Check Access */
        if (!check_user_access('role', 'delete')) {
            return redirect(route('notfound'));
        }
        try {
            $role->delete();
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete role', $e);
            return Response::error();
        }
    }
}
