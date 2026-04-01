<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('category', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = Category::with('store');

            if (Auth::user()->role->level > 1) {
                $data->where('store_id', Auth::user()->store_id);
            }

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

        $data = Category::where('name', 'like', '%' . $keyword . '%');

        if (Auth::user()->role->level > 1) {
            $data->where('store_id', Auth::user()->store_id);
        }

        $data = $data->limit(50)
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
        if (!check_user_access('category', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.category.index');
    }

    public function edit(Category $category)
    {
        /* Check Access */
        if (!check_user_access('category', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.category.edit', compact(['category']));
    }

    public function update(Request $request, Category $category)
    {
        /* Check Access */
        if (!check_user_access('category', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($category->store_id !== Auth::user()->store_id && Auth::user()->role->level > 1) {
            return Response::error('Unauthorized', 401);
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'nullable',
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
            'updated_by' => Auth::id()
        ];

        /* Update Data */
        try {
            $category->update($data);
            Cache::forget('categories-' . $category->store_id);
            Cache::forget('category_slug_' . $category->slug);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update category', $e);
            return Response::error();
        }
    }
}
