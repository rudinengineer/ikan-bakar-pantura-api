<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function all()
    {
        $categories = Cache::rememberForever('categories', function () {
            return Category::orderBy('order_number', 'asc')->get();
        });

        return Response::successWithData(
            CategoryResource::collection($categories)
        );
    }

    public function datatables(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $limit = (int) $request->query('limit', 10);
        $search = trim((string) $request->query('search', ''));

        $query = Category::query()
            ->select(['id', 'name', 'slug', 'created_at']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $categories = $query
            ->latest('id')
            ->paginate($limit)
            ->withQueryString();

        return Response::successWithData([
            'categories' => $categories->items(),
            'data_total' => $categories->total()
        ]);
        // return response()->json([
        //     'data' => $categories->items(),
        //     'meta' => [
        //         'current_page' => $categories->currentPage(),
        //         'last_page' => $categories->lastPage(),
        //         'per_page' => $categories->perPage(),
        //         'total' => $categories->total(),
        //         'from' => $categories->firstItem(),
        //         'to' => $categories->lastItem(),
        //     ],
        //     'links' => [
        //         'next' => $categories->nextPageUrl(),
        //         'prev' => $categories->previousPageUrl(),
        //     ],
        // ]);
    }

    public function update(Request $request, Category $category)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name' => [
                'required' => 'Kategori wajib diisi'
            ]
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
            'slug' => intval($category->order_number) === 1 ? 'basic' : Str::slug($request->name) . random_int(1000, 9999),
            'updated_by' => Auth::id()
        ];

        try {
            $category->update($data);
            Cache::forget('categories');
            Cache::forget('category_slug_' . $category->slug);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update category', $e);
            return Response::error();
        }
    }
}
