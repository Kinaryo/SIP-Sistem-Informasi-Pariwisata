<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        
        // Buat data default jika tabel kosong agar tidak error di view
        if (!$setting) {
            $setting = Setting::create([
                'office_name' => 'Kantor Pusat',
                'longitude' => '0',
                'latitude' => '0'
            ]);
        }

        Log::info('Admin membuka halaman pengaturan kantor');
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request, $id)
    {

        // 1. Validasi
        $validated = $request->validate([
            'office_name' => 'required|string|max:255',
            'longitude'   => 'required|string|max:50',
            'latitude'    => 'required|string|max:50',
        ]);

        try {
            $setting = Setting::findOrFail($id);
            $setting->update($validated);

            Log::info('Setting berhasil diperbarui', [
                'id' => $setting->id,
                'admin_id' => auth()->id(),
                'data' => $validated
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pengaturan kantor berhasil diperbarui',
                'data' => $setting
            ], 200);

        } catch (\Exception $e) {
            Log::error('Gagal memperbarui setting: ' . $e->getMessage(), [
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }
}