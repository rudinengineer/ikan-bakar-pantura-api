<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    public function datatables()
    {
        /* Check Access */
        if (!check_user_access('store', 'read')) {
            return redirect(route('notfound'));
        }

        if (request()->ajax()) {
            $data = Store::query();

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

        $data = Store::where('name', 'like', '%' . $keyword . '%')
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
        if (!check_user_access('store', 'read')) {
            return redirect(route('notfound'));
        }

        return view('pages.store.index');
    }

    public function create()
    {
        /* Check Access */
        if (!check_user_access('store', 'create')) {
            return redirect(route('notfound'));
        }

        return view('pages.store.create');
    }

    public function edit(Store $store)
    {
        /* Check Access */
        if (!check_user_access('store', 'update')) {
            return redirect(route('notfound'));
        }

        return view('pages.store.edit', compact(['store']));
    }

    public function store(Request $request)
    {
        /* Check Access */
        if (!check_user_access('store', 'create')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'logo' => 'required|image',
            'area' => 'required',
            'address' => 'required',
            'whatsapp' => 'required|int',
            'bank' => 'required',
            'account_number' => 'required|int',
            'account_name' => 'required',
        ], [
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'logo' => [
                'image' => 'Harap mengupload gambar'
            ],
            'area' => [
                'required' => 'Area wajib diisi'
            ],
            'address' => [
                'required' => 'Alamat wajib diisi'
            ],
            'whatsapp' => [
                'required' => 'WhatsApp wajib diisi',
                'int' => 'WhatsApp harus berupa angka'
            ],
            'bank' => [
                'required' => 'Bank wajib diisi'
            ],
            'account_number' => [
                'required' => 'Nomor rekening wajib diisi',
                'int' => 'Nomor rekening harus berupa angka'
            ],
            'account_name' => [
                'required' => 'Nama pemilik rekening wajib diisi'
            ],
        ]);

        if ($validation->fails()) {
            return Response::errorWithData(
                collect($validation->errors()->messages())
                    ->map(fn($msg) => $msg[0]),
                'validation error',
                400
            );
        }

        $file = $request->file('logo');
        $filename = $file->hashName();
        $file->move('uploads', $filename);

        $data = [
            'name' => $request->name,
            'logo' => $filename,
            'area' => $request->area,
            'address' => $request->address,
            'whatsapp' => $request->whatsapp,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'created_by' => Auth::id()
        ];

        /* Insert Data */
        DB::beginTransaction();
        try {
            $store = Store::create($data);

            Category::insert([
                [
                    'store_id' => $store->id,
                    'name' => 'Kategori 1',
                    'slug' => 'kategori-1-' . $store->id,
                    'order_number' => 1,
                ],
                [
                    'store_id' => $store->id,
                    'name' => 'Kategori 2',
                    'slug' => 'kategori-2-' . $store->id,
                    'order_number' => 2,
                ]
            ]);

            DB::commit();
            Cache::forget('stores');

            return Response::success();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('failed to save store', $e);
            return Response::error();
        }
    }

    public function update(Request $request, Store $store)
    {
        /* Check Access */
        if (!check_user_access('store', 'update')) {
            return redirect(route('notfound'));
        }

        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'logo' => 'nullable|image',
            'area' => 'required',
            'address' => 'required',
            'bank' => 'required',
            'account_number' => 'required|int',
            'account_name' => 'required',
        ], [
            'name' => [
                'required' => 'Nama wajib diisi'
            ],
            'logo' => [
                'image' => 'Harap mengupload gambar'
            ],
            'area' => [
                'required' => 'Area wajib diisi'
            ],
            'address' => [
                'required' => 'Alamat wajib diisi'
            ],
            'whatsapp' => [
                'required' => 'WhatsApp wajib diisi'
            ],
            'bank' => [
                'required' => 'Bank wajib diisi'
            ],
            'account_number' => [
                'required' => 'Nomor rekening wajib diisi',
                'int' => 'Nomor rekening harus berupa angka'
            ],
            'account_name' => [
                'required' => 'Nama pemilik rekening wajib diisi'
            ],
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
            'area' => $request->area,
            'address' => $request->address,
            'whatsapp' => $request->whatsapp,
            'bank' => $request->bank,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'updated_by' => Auth::id()
        ];

        $file = $request->file('logo');
        if ($file && $file->isValid()) {
            $filename = $file->hashName();
            $file->move('uploads', $filename);
            $data['logo'] = $filename;
        }

        /* Update Data */
        try {
            $oldLogo = $store->logo;
            $store->update($data);

            if ($file && $file->isValid()) {
                if (file_exists('uploads/' . $oldLogo)) {
                    unlink('uploads/' . $oldLogo);
                }
            }

            Cache::forget('stores');
            Cache::forget('store-' . $store->id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update store', $e);
            return Response::error();
        }
    }

    public function destroy(Store $store)
    {
        /* Check Access */
        if (!check_user_access('store', 'delete')) {
            return redirect(route('notfound'));
        }
        try {
            $oldLogo = $store->logo;
            $store->delete();

            if (file_exists('uploads/' . $oldLogo)) {
                unlink('uploads/' . $oldLogo);
            }

            Cache::forget('stores');
            Cache::forget('store-' . $store->id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to delete store', $e);
            return Response::error();
        }
    }
}
