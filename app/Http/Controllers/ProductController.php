<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Repository\CategoryRepository;
use App\Http\Repository\PacketRepository;
use App\Http\Repository\ProductRepository;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function getByPacket($slug)
    {
        $packet = PacketRepository::findBySlug($slug);
        if (!$packet) {
            return Response::error('Packet not found', 404);
        }

        $products = ProductRepository::getByPacket($packet->id);

        return Response::successWithData(
            ProductResource::collection($products)
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

        $query = Product::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $data = $query
            ->latest('id')
            ->paginate($limit)
            ->withQueryString();

        return Response::successWithData([
            'products' => $data->items(),
            'data_total' => $data->total()
        ]);
    }

    public function select2(Request $request)
    {
        $data = Product::query();

        if ($request->keyword) {
            $data->where('name', 'like', '%' . $request->keyword . '%');
        }

        return Response::successWithData($data->limit(20)->get());
    }

    public function store(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|int',
            'image' => 'required|image',
        ], [
            'name' => [
                'required' => 'Nama menu wajib diisi'
            ],
            'price' => [
                'required' => 'Harga wajib diisi',
                'int' => 'Harga harus berupa angka'
            ],
            'image' => [
                'required' => 'Harap mengupload gambar',
                'image' => 'Harap mengupload gambar'
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

        try {
            /* Upload Image */
            $file = $request->file('image');
            $filename = $file->hashName();
            $file->move('assets/images', $filename);

            $data = [
                'store_id' => config('app.store_id'),
                'name' => $request->name,
                'slug' => Str::slug($request->name) . random_int(1000, 9999),
                'price' => $request->price,
                'image' => $filename,
                'created_by' => Auth::id()
            ];

            Product::create($data);
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to create product', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Product $product)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|int',
            'image' => 'nullable|image',
        ], [
            'name' => [
                'required' => 'Nama menu wajib diisi'
            ],
            'price' => [
                'required' => 'Harga wajib diisi',
                'int' => 'Harga harus berupa angka'
            ],
            'image' => [
                'image' => 'Harap mengupload gambar'
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

        try {
            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name) . random_int(1000, 9999),
                'price' => $request->price,
                'updated_by' => Auth::id()
            ];

            /* Upload Image */
            $file = $request->file('image');
            if ($file && $file->isValid()) {
                $filename = $file->hashName();
                $file->move('assets/images', $filename);
                $data['image'] = $filename;
            }

            $product->update($data);
            Cache::forget('product_' . $product->id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update product', $e);
            return Response::error();
        }
    }

    public function delete(Product $product)
    {
        try {
            $product->delete();

            /* Delete Image */
            if (file_exists('assets/images' . $product->image)) {
                unlink('assets/images' . $product->image);
            }

            Cache::forget('product_' . $product->id);
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete product', $e);
            return Response::error();
        }
    }
}
