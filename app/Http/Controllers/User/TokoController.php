<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;

class TokoController extends Controller
{
    /**
     * ================= CLOUDINARY =================
     * Pakai CLOUDINARY_URL dari .env
     */
    private function cloudinary()
    {
        $url = env('CLOUDINARY_URL');

        if (!$url) {
            throw new \Exception("CLOUDINARY_URL belum diset di .env");
        }

        return new Cloudinary($url);
    }

    /**
     * ================= UPLOAD =================
     */
    private function uploadLogo($file, $folder = 'toko_logos')
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception("File tidak valid");
        }

        Log::info("[Cloudinary] Upload dimulai oleh User: " . Auth::id());

        $result = $this->cloudinary()->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
                'resource_type' => 'image',
                'overwrite' => true,
            ]
        );

        if (!isset($result['secure_url'])) {
            throw new \Exception("Upload gagal, URL tidak ditemukan");
        }

        Log::info("[Cloudinary] Upload sukses", [
            'public_id' => $result['public_id'],
            'url' => $result['secure_url']
        ]);

        return [
            'url' => $result['secure_url'],
            'public_id' => $result['public_id'],
        ];
    }

    /**
     * ================= DELETE CLOUDINARY =================
     */
    private function deleteLogo($publicId)
    {
        if (!$publicId) return;

        try {
            Log::warning("[Cloudinary] Hapus file: " . $publicId);

            $this->cloudinary()->uploadApi()->destroy($publicId);

        } catch (\Exception $e) {
            Log::error("[Cloudinary] Gagal hapus: " . $e->getMessage());
        }
    }

    /**
     * ================= INDEX =================
     */
    public function index(Request $request)
    {
        $toko = Toko::where('user_id', Auth::id())->first();

        Log::info('[Toko] Data', $toko ? $toko->toArray() : []);

        if ($request->ajax()) {
            return response()->json([
                'status' => $toko ? 'has_toko' : 'no_toko',
                'toko' => $toko
            ]);
        }

        return view('dashboard.toko.index', compact('toko'));
    }

    /**
     * ================= STORE =================
     * Jika upload gagal → rollback total
     */
    public function store(Request $request)
    {
        if (Toko::where('user_id', Auth::id())->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah punya toko'
            ], 422);
        }

        $request->validate([
            'nama_toko' => 'required|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048', // wajib biar aman
        ]);

        DB::beginTransaction();

        try {
            // WAJIB upload dulu
            $upload = $this->uploadLogo($request->file('logo'));

            // Baru simpan DB
            $toko = Toko::create([
                'user_id' => Auth::id(),
                'nama_toko' => $request->nama_toko,
                'slug' => Str::slug($request->nama_toko) . '-' . Str::random(5),
                'deskripsi' => $request->deskripsi,
                'telepon' => $request->telepon,
                'logo' => $upload['url'],
                'image_public_id' => $upload['public_id'],
                'telepon_aktif' => $request->boolean('telepon_aktif')
            ]);

            DB::commit();

            Log::info("[Toko] Berhasil dibuat ID: " . $toko->id);

            return response()->json([
                'status' => 'success',
                'message' => 'Toko berhasil dibuat'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("[Toko] Store gagal: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Upload gagal, data tidak disimpan'
            ], 500);
        }
    }

    /**
     * ================= UPDATE =================
     */
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);

        if ($toko->user_id !== Auth::id()) abort(403);

        $request->validate([
            'nama_toko' => 'required|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $logo = $toko->logo;
            $publicId = $toko->image_public_id;

            // Jika ada logo baru
            if ($request->hasFile('logo')) {

                // upload dulu
                $upload = $this->uploadLogo($request->file('logo'));

                // kalau sukses baru hapus lama
                if ($publicId) {
                    $this->deleteLogo($publicId);
                }

                $logo = $upload['url'];
                $publicId = $upload['public_id'];
            }

            $toko->update([
                'nama_toko' => $request->nama_toko,
                'deskripsi' => $request->deskripsi,
                'telepon' => $request->telepon,
                'logo' => $logo,
                'image_public_id' => $publicId,
                'telepon_aktif' => $request->boolean('telepon_aktif')
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Toko berhasil diupdate'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("[Toko] Update gagal: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Update gagal'
            ], 500);
        }
    }

    /**
     * ================= DELETE =================
     */
    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);

        if ($toko->user_id !== Auth::id()) abort(403);

        try {
            if ($toko->image_public_id) {
                $this->deleteLogo($toko->image_public_id);
            }

            $toko->delete();

            Log::warning("[Toko] Berhasil dihapus ID: " . $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Toko berhasil dihapus'
            ]);

        } catch (\Exception $e) {

            Log::error("[Toko] Delete gagal: " . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal hapus toko'
            ], 500);
        }
    }
}