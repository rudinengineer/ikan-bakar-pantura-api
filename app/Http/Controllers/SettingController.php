<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Log;
use App\Http\Helpers\Response;
use App\Http\Repository\SettingRepository;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function downloadQris()
    {
        $setting = SettingRepository::get();

        return response()->download('assets/images/' . $setting->qris, $setting->qris);
    }

    public function index()
    {
        $setting = SettingRepository::get();

        return Response::successWithData(
            new SettingResource($setting)
        );
    }

    public function update(Request $request)
    {
        /* Validate Request */
        $validation = Validator::make($request->all(), [
            'bank' => 'required',
            'account_name' => 'required',
            'account_number' => 'required|int',
            'qris' => 'nullable|image',
        ], [
            'bank' => [
                'required' => 'Nama menu wajib diisi'
            ],
            'account_name' => [
                'required' => 'Nama menu wajib diisi'
            ],
            'account_number' => [
                'required' => 'Harga wajib diisi',
                'int' => 'Harga harus berupa angka'
            ],
            'qris' => [
                'image' => 'Harap mengupload gambar QRIS'
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
                'bank' => $request->bank,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
            ];

            $file = $request->file('qris');
            if ($file && $file->isValid()) {
                $filename = $file->hashName();
                $file->move('assets/images', $filename);
                $data['qris'] = $filename;
            }

            $setting = Setting::first();
            $setting->update($data);

            Cache::forget('setting');
            return Response::success();
        } catch (\Throwable $e) {
            Log::error('failed to update setting', $e);
            return Response::error();
        }
    }
}
