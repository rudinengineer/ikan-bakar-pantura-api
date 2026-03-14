<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Repository\CategoryRepository;
use App\Http\Resources\PacketResource;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PacketController extends Controller
{
    public function getByCategory($slug)
    {
        $category = CategoryRepository::findBySlug($slug);
        if (!$category) {
            return Response::error('Category not found', 404);
        }

        $packets = Cache::rememberForever('packets_category_' . $category->id, function () use ($category) {
            return Packet::where('category_id', $category->id)
                ->orderBy('order_number', 'asc')
                ->get();
        });

        return Response::successWithData(
            PacketResource::collection($packets)
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

        $query = Packet::with('category');

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
            'packets' => $data->items(),
            'data_total' => $data->total()
        ]);
    }

    public function store(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'category_id' => 'required|int',
            'name' => 'required',
            'image' => 'required|image',
            'order_number' => 'required|int'
        ], [
            'category_id' => [
                'required' => 'Harap memilih kategori',
                'int' => 'Kategori harus berupa angka'
            ],
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'image' => [
                'required' => 'Harap mengupload gambar',
                'image' => 'Harap mengupload gambar'
            ],
            'order_number' => [
                'required' => 'Urutan wajib diisi',
                'int' => 'Urutan harus berupa angka'
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
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . random_int(1000, 9999),
                'order_number' => $request->order_number,
                'image' => $filename,
                'created_by' => Auth::id()
            ];

            Cache::forget('packets_category_' . $request->category_id);
            Packet::create($data);
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to create packet', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Packet $packet)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'category_id' => 'required|int',
            'name' => 'required',
            'image' => 'nullable|image',
            'order_number' => 'required|int'
        ], [
            'category_id' => [
                'required' => 'Harap memilih kategori',
                'int' => 'Kategori harus berupa angka'
            ],
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'image' => [
                'image' => 'Harap mengupload gambar'
            ],
            'order_number' => [
                'required' => 'Urutan wajib diisi',
                'int' => 'Urutan harus berupa angka'
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
                'category_id' => $request->category_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . random_int(1000, 9999),
                'order_number' => $request->order_number,
                'updated_by' => Auth::id()
            ];

            /* Upload Image */
            $file = $request->file('image');
            if ($file && $file->isValid()) {
                $filename = $file->hashName();
                $file->move('assets/images', $filename);
                $data['image'] = $filename;
            }

            $packet->update($data);
            Cache::forget('packets_category_' . $request->category_id);
            Cache::forget('packet_slug_' . $packet->slug);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update packet', $e);
            return Response::error();
        }
    }

    public function delete(Packet $packet)
    {
        try {
            $packet->delete();

            /* Delete Image */
            if (file_exists('assets/images/' . $packet->image)) {
                unlink('assets/images/' . $packet->image);
            }

            Cache::forget('packets_category_' . $packet->category_id);
            Cache::forget('packet_slug_' . $packet->slug);
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete packet', $e);
            return Response::error();
        }
    }
}
