<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Repository\StoreRepository;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function all(Request $request)
    {
        $store = StoreRepository::find($request->store_id);
        if (!$store) {
            return Response::error('Store not found', 404);
        }

        $categories = Cache::rememberForever('categories-' . $store->id, function () use ($store) {
            return Category::where('store_id', $store->id)
                ->orderBy('order_number', 'asc')
                ->get();
        });

        return Response::successWithData(
            CategoryResource::collection($categories)
        );
    }
}
