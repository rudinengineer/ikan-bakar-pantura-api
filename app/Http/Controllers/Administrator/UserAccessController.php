<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Role;
use App\Models\UserAccess;
use App\Models\UserAccessItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserAccessController extends Controller
{
    public function datatables(Role $role)
    {
        /* Check Access */
        if (!check_user_access('user-acccess', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = UserAccessItem::with([
                'user_access' => function ($query) use ($role) {
                    $query->where('role_id', $role->id);
                }
            ])->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }

        return response()->json([
            'status' => false
        ]);
    }

    public function edit(Role $role)
    {
        /* Check Access */
        if (!check_user_access('user-acccess', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.user-access.index', compact(['role']));
    }

    public function update(Request $request, Role $role)
    {
        /* Check Access */
        if (!check_user_access('user-acccess', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'user_access_id' => 'nullable|int',
            'user_access_item_id' => 'required|int',
            'access_read' => 'nullable|boolean',
            'access_create' => 'nullable|boolean',
            'access_update' => 'nullable|boolean',
            'access_delete' => 'nullable|boolean',
            'access_member' => 'nullable|boolean',
            'access_menu' => 'nullable|boolean',
            'access_approve' => 'nullable|boolean',
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        /* Parse Request */
        $data = [
            'role_id' => $role->id,
            'user_access_item_id' => $request->user_access_item_id,
            'updated_by' => Auth::id()
        ];

        if ($request->has('access_read')) {
            $data['access_read'] = $request->boolean('access_read');
        }

        if ($request->has('access_create')) {
            $data['access_create'] = $request->boolean('access_create');
        }

        if ($request->has('access_update')) {
            $data['access_update'] = $request->boolean('access_update');
        }

        if ($request->has('access_delete')) {
            $data['access_delete'] = $request->boolean('access_delete');
        }

        if ($request->has('access_member')) {
            $data['access_member'] = $request->boolean('access_member');
        }

        if ($request->has('access_menu')) {
            $data['access_menu'] = $request->boolean('access_menu');
        }

        if ($request->has('access_approve')) {
            $data['access_approve'] = $request->boolean('access_approve');
        }

        if ($request->integer('user_access_id')) {
            /* Update Data */
            try {
                UserAccess::where('id', $request->integer('user_access_id'))
                    ->update($data);

                Cache::forget('user_access_' . $role->id);

                return Response::success();
            } catch (\Throwable $e) {
                Log::error('failed to update user access', $e);
                return Response::error();
            }
        } else {
            /* Insert Data */
            try {
                UserAccess::create($data);

                Cache::forget('user_access_' . $role->id);

                return Response::success();
            } catch (\Throwable $e) {
                Log::error('failed to update user access', $e);
                return Response::error();
            }
        }
    }

    public function update_all(Request $request, Role $role)
    {
        /* Check Access */
        if (!check_user_access('user-acccess', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'user_access_item_id' => 'required|int',
            'user_access_id' => 'nullable|int',
            'access_value' => 'required|boolean',
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
            'role_id' => $role->id,
            'user_access_item_id' => $request->user_access_item_id,
            'access_read' => $request->boolean('access_value'),
            'access_create' => $request->boolean('access_value'),
            'access_update' => $request->boolean('access_value'),
            'access_delete' => $request->boolean('access_value'),
            'access_menu' => $request->boolean('access_value'),
            'access_member' => $request->boolean('access_value'),
            'access_approve' => $request->boolean('access_value'),
            'updated_by' => Auth::id()
        ];

        if ($request->integer('user_access_id')) {
            /* Update Data */
            try {
                UserAccess::where('id', $request->integer('user_access_id'))
                    ->update($data);

                Cache::forget('user_access_' . $role->id);

                return Response::success();
            } catch (\Throwable $e) {
                Log::error('failed to update user access', $e);
                return Response::error();
            }
        } else {
            /* Insert Data */
            try {
                UserAccess::create($data);

                Cache::forget('user_access_' . $role->id);

                return Response::success();
            } catch (\Throwable $e) {
                Log::error('failed to create user access', $e);
                return Response::error();
            }
        }
    }
}
