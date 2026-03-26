<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('product', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = Product::with('store');

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

        $data = Product::where('name', 'like', '%' . $keyword . '%');

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
        if (!check_user_access('product', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.product.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('product', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.product.create');
    }

    public function edit(Product $product)
    {
        /* Check Access */
        if (!check_user_access('product', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.product.edit', compact(['product']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('product', 'create')) {
            return redirect(route('notfound'));
        }

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

        $file = $request->file('image');
        $filename = $file->hashName();
        $file->move('uploads', $filename);

        $data = [
            'store_id' => Auth::user()->store_id,
            'name' => $request->name,
            'slug' => strtolower(Str::slug($request->name)) . '-' . random_int(1000, 9999),
            'price' => $request->integer('price'),
            'image' => $filename,
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        try {
            Product::create($data);
            Cache::forget('products_packet_*');

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to store product', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Product $product)
    {
        /* Check Access */
        if (!check_user_access('product', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($product->store_id !== Auth::user()->store_id && Auth::user()->role->level > 1) {
            return Response::error('Unauthorized', 401);
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required|int',
            'image' => 'nullable|image'
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

        $data = [
            'name' => $request->name,
            'slug' => strtolower(Str::slug($request->name)) . '-' . random_int(1000, 9999),
            'price' => $request->integer('price'),
            'updated_by' => Auth::id()
        ];

        $file = $request->file('image');
        if ($file && $file->isValid()) {
            $filename = $file->hashName();
            $file->move('uploads', $filename);
            $data['image'] = $filename;
        }

        /* Update Data */
        try {
            $oldImage = $product->image;
            $product->update($data);

            if ($file && $file->isValid()) {
                if (file_exists('uploads/' . $oldImage)) {
                    unlink('uploads/' . $oldImage);
                }
            }

            Cache::forget('products_packet_*');

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update product', $e);
            return Response::error();
        }
    }

    public function destroy(Product $product)
    {
        /* Check Access */
        if (!check_user_access('product', 'delete')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($product->store_id !== Auth::user()->store_id && Auth::user()->role->level > 1) {
            return Response::error('Unauthorized', 401);
        }

        try {
            $oldImage = $product->image;
            $product->delete();

            if (file_exists('uploads/' . $oldImage)) {
                unlink('uploads/' . $oldImage);
            }

            Cache::forget('products_packet_*');

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete product', $e);
            return Response::error();
        }
    }
}
