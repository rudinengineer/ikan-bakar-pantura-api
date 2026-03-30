<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Packet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PacketController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('packet', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = Packet::with(['store', 'category']);

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

        $data = Packet::where('name', 'like', '%' . $keyword . '%');

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
        if (!check_user_access('packet', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.packet.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('packet', 'create')) {
            return redirect(route('notfound'));
        }

        $lastOrderNumber = Packet::where('store_id', Auth::user()->store_id)
            ->orderBy('order_number', 'desc')
            ->first();

        return view('pages.packet.create.index', compact('lastOrderNumber'));
    }

    public function edit(Packet $packet)
    {
        /* Check Access */
        if (!check_user_access('packet', 'update')) {
            return redirect(route('notfound'));
        }

        $packet->load('category');

        return view('pages.packet.edit.index', compact(['packet']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('packet', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'category_id' => 'required|int',
            // 'name' => 'required',
            'image' => 'required|image',
            'order_number' => 'required|int'
        ], [
            'category_id' => [
                'required' => 'Kategori wajib diisi'
            ],
            'name' => [
                'required' => 'Nama paket wajib diisi'
            ],
            'image' => [
                'required' => 'Harap mengupload gambar',
                'image' => 'Harap mengupload gambar'
            ],
            'order_number' => [
                'required' => 'Urutan wajib diisi',
                'int' => 'Urutan harus berupa angka',
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
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => strtolower(Str::slug($request->name)) . '-' . random_int(1000, 9999),
            'image' => $filename,
            'order_number' => $request->order_number,
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        try {
            Packet::create($data);
            Cache::forget('packets_category_' . $request->category_id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to store packet', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Packet $packet)
    {
        /* Check Access */
        if (!check_user_access('packet', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($packet->store_id !== Auth::user()->store_id && Auth::user()->role->level > 1) {
            return Response::error('Unauthorized', 401);
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'category_id' => 'required|int',
            // 'name' => 'required',
            'image' => 'nullable|image',
            'order_number' => 'required|int'
        ], [
            'category_id' => [
                'required' => 'Kategori wajib diisi'
            ],
            'name' => [
                'required' => 'Nama paket wajib diisi'
            ],
            'image' => [
                'image' => 'Harap mengupload gambar'
            ],
            'order_number' => [
                'required' => 'Urutan wajib diisi',
                'int' => 'Urutan harus berupa angka',
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
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => strtolower(Str::slug($request->name)) . '-' . random_int(1000, 9999),
            'order_number' => $request->order_number,
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
            $oldImage = $packet->image;
            $packet->update($data);

            if ($file && $file->isValid()) {
                if (file_exists('uploads/' . $oldImage)) {
                    unlink('uploads/' . $oldImage);
                }
            }

            Cache::forget('packets_category_' . $request->category_id);
            Cache::forget('packet_slug_' . $packet->slug);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update packet', $e);
            return Response::error();
        }
    }

    public function destroy(Packet $packet)
    {
        /* Check Access */
        if (!check_user_access('packet', 'delete')) {
            return redirect(route('notfound'));
        }

        /* Validate Access */
        if ($packet->store_id !== Auth::user()->store_id && Auth::user()->role->level > 1) {
            return Response::error('Unauthorized', 401);
        }

        try {
            $oldImage = $packet->image;
            $packet->delete();

            if (file_exists('uploads/' . $oldImage)) {
                unlink('uploads/' . $oldImage);
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
