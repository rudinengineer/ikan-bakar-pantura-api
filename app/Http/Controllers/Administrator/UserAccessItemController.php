<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\UserAccessItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserAccessItemController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('user-acccess-item', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = UserAccessItem::query();

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
        if (!check_user_access('user-acccess-item', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.user-access-item.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('user-acccess-item', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.user-access-item.create');
    }

    public function edit(UserAccessItem $useraccessitem)
    {
        /* Check Access */
        if (!check_user_access('user-acccess-item', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.user-access-item.edit', compact(['useraccessitem']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('user-acccess-item', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'access_name' => 'required',
            'access_link' => 'required|regex:/^[A-Za-z_-]+$/',
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

        $data = [
            'access_name' => $request->access_name,
            'access_link' => $request->access_link,
            'access_read' => $request->boolean('access_read'),
            'access_create' => $request->boolean('access_create'),
            'access_update' => $request->boolean('access_update'),
            'access_delete' => $request->boolean('access_delete'),
            'access_member' => $request->boolean('access_member'),
            'access_menu' => $request->boolean('access_menu'),
            'access_approve' => $request->boolean('access_approve'),
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        try {
            UserAccessItem::create($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to store user access item', $e);
            return Response::error();
        }
    }

    public function update(Request $request, UserAccessItem $useraccessitem)
    {
        /* Check Access */
        if (!check_user_access('user-acccess-item', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'access_name' => 'required',
            'access_link' => 'required|regex:/^[A-Za-z_-]+$/',
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

        $data = [
            'access_name' => $request->access_name,
            'access_link' => $request->access_link,
            'access_read' => $request->boolean('access_read'),
            'access_create' => $request->boolean('access_create'),
            'access_update' => $request->boolean('access_update'),
            'access_delete' => $request->boolean('access_delete'),
            'access_member' => $request->boolean('access_member'),
            'access_menu' => $request->boolean('access_menu'),
            'access_approve' => $request->boolean('access_approve'),
            'updated_by' => Auth::id()
        ];

        /* Update Data */
        try {
            $useraccessitem->update($data);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update user access item', $e);
            return Response::error();
        }
    }

    public function destroy(UserAccessItem $useraccessitem)
    {
        /* Check Access */
        if (!check_user_access('user-acccess-item', 'delete')) {
            return redirect(route('notfound'));
        }

        try {
            $useraccessitem->delete();
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete user access item', $e);
            return Response::error();
        }
    }
}
