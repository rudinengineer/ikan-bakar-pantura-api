<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class AppSettingController extends Controller
{
    public function index()
    {
        /* Check Access */
        if (!check_user_access('app-setting', 'update')) {
            return redirect(route('notfound'));
        }

        $store = Store::find(Auth::user()->store_id);

        return view('pages.app-setting.index', compact(['store']));
    }

    public function update(Request $request)
    {
        /* Check Access */
        if (!check_user_access('app-setting', 'update')) {
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
            'qris' => 'nullable|image',
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
            'qris' => [
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
                'area' => $request->area,
                'address' => $request->address,
                'whatsapp' => $request->whatsapp,
                'bank' => $request->bank,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
                'qris' => $request->qris,
                'updated_by' => Auth::id()
            ];

            /* Upload Logo */
            $file = $request->file('logo');
            if ($file && $file->isValid()) {
                $filename = $file->hashName();
                $file->move('uploads', $filename);
                $data['logo'] = $filename;
            }

            /* Upload QRIS */
            $file = $request->file('qris');
            if ($file && $file->isValid()) {
                $filename = $file->hashName();
                $file->move('uploads', $filename);
                $data['qris'] = $filename;
            }

            /* Update App Setting */
            Store::where('id', Auth::user()->store_id)
                ->update($data);

            /* Delete Cache */
            Cache::forget('setting');
            Cache::forget('store-' . Auth::user()->store_id);

            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update app setting', $e);
            return Response::error();
        }
    }
}
