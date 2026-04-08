<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Cloudinary\Cloudinary;

class AdminTokoController extends Controller
{
    // ================= CLOUDINARY =================
    private function cloudinary()
    {
        $url = env('CLOUDINARY_URL');

        if (!$url) {
            throw new \Exception("CLOUDINARY_URL belum diset");
        }

        return new Cloudinary($url);
    }

    // ================= UPLOAD =================
    private function uploadLogo($file, $folder = 'toko_logos')
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception("File tidak valid");
        }

        Log::info("[ADMIN TOKO] Upload logo dimulai");

        $result = $this->cloudinary()->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
                'resource_type' => 'image'
            ]
        );

        return [
            'url' => $result['secure_url'],
            'public_id' => $result['public_id']
        ];
    }

    // ================= DELETE CLOUDINARY =================
    private function deleteLogo($publicId)
    {
        if (!$publicId) return;

        try {
            $this->cloudinary()->uploadApi()->destroy($publicId);
        } catch (\Exception $e) {
            Log::error("Gagal hapus logo: " . $e->getMessage());
        }
    }

    // ================= UPLOAD IMAGE =================
    private function uploadImage($file, $folder = 'produk')
    {
        if (!$file || !file_exists($file->getRealPath())) {
            throw new \Exception("File tidak valid");
        }

        $upload = $this->cloudinary()->uploadApi()->upload(
            $file->getRealPath(),
            ['folder' => $folder]
        );

        return [
            'url' => $upload['secure_url'],
            'public_id' => $upload['public_id'],
        ];
    }

    // ================= DELETE IMAGE =================
    private function deleteImage($publicId)
    {
        if (!$publicId) return;

        try {
            $this->cloudinary()->uploadApi()->destroy($publicId);
        } catch (\Exception $e) {
            Log::error("Gagal hapus gambar", [
                'error' => $e->getMessage()
            ]);
        }
    }

    // ================= VALIDASI =================
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
        ]);
    }

    // ================= INDEX =================
public function index(Request $request)
{
    $query = Toko::with('user')
        ->withCount('produks') //  INI YANG PENTING
        ->latest();

    // 🔍 SEARCH
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('nama_toko', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        });
    }

    //  FILTER TELEPON AKTIF
    if ($request->filled('status')) {
        if ($request->status === 'active') {
            $query->where('telepon_aktif', 1);
        } elseif ($request->status === 'inactive') {
            $query->where('telepon_aktif', 0);
        }
    }

    $tokos = $query->paginate(10)->withQueryString();

    return view('admin.toko.index', compact('tokos'));
}

    // ================= SHOW =================
    public function show(Request $request, $id)
    {
        $toko = Toko::with('user')->findOrFail($id);

        $query = Produk::where('user_id', $toko->user_id)
            ->with('user')
            ->latest();

        if ($request->filled('search')) {
            $query->where('nama_produk', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }

        if ($request->filled('verified')) {
            $query->where('is_verified', $request->verified);
        }

        $produks = $query->paginate(10)->withQueryString();

        return view('admin.toko.show', compact('toko', 'produks'));
    }

    // ================= CREATE =================
    public function create()
    {
        $users = User::whereDoesntHave('toko')->get();

        return view('admin.toko.create', compact('users'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_toko' => 'required|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'user_id.required' => 'User wajib dipilih',
            'nama_toko.required' => 'Nama toko wajib diisi',
            'logo.required' => 'Logo wajib diupload'
        ]);

        // 🔥 CEK 1 USER 1 TOKO
        if (Toko::where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User ini sudah memiliki toko');
        }

        DB::beginTransaction();

        try {

            $upload = $this->uploadLogo($request->file('logo'));

            Toko::create([
                'user_id' => $request->user_id,
                'nama_toko' => $request->nama_toko,
                'slug' => Str::slug($request->nama_toko) . '-' . Str::random(5),
                'deskripsi' => $request->deskripsi,
                'telepon' => $request->telepon,
                'logo' => $upload['url'],
                'image_public_id' => $upload['public_id'],
                'telepon_aktif' => $request->has('telepon_aktif') ? 1 : 0
            ]);

            DB::commit();

            return redirect()
                ->route('admin.toko.index')
                ->with('success', 'Toko berhasil ditambahkan');
        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', 'Gagal menambah toko');
        }
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $toko = Toko::findOrFail($id);
        return view('admin.toko.edit', compact('toko'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);

        $request->validate([
            'nama_toko' => 'required|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {

            $logo = $toko->logo;
            $publicId = $toko->image_public_id;

            if ($request->hasFile('logo')) {

                $upload = $this->uploadLogo($request->file('logo'));

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
                'telepon_aktif' => $request->has('telepon_aktif') ? 1 : 0
            ]);

            DB::commit();

            return redirect()
                ->route('admin.toko.index')
                ->with('success', 'Toko berhasil diperbarui');
        } catch (\Exception $e) {

            DB::rollBack();

            Log::error("[ADMIN TOKO] Update gagal: " . $e->getMessage());

            return back()->with('error', 'Gagal update toko');
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        try {

            $toko = Toko::findOrFail($id);

            if ($toko->image_public_id) {
                $this->deleteLogo($toko->image_public_id);
            }

            $toko->delete();

            return response()->json([
                'message' => 'Toko berhasil dihapus'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Gagal menghapus toko'
            ], 500);
        }
    }

    // ================= TOGGLE TELEPON =================
    public function toggleTelepon($id)
    {
        $toko = Toko::findOrFail($id);

        $toko->telepon_aktif = !$toko->telepon_aktif;
        $toko->save();

        return response()->json([
            'message' => 'Status telepon berhasil diubah',
            'status' => $toko->telepon_aktif
        ]);
    }


    public function createByToko($tokoId)
    {
        $toko = Toko::with('user')->findOrFail($tokoId);

        return view('admin.toko.create-produk-by-toko', compact('toko'));
    }

    public function storeByToko(Request $request, $tokoId)
    {
        try {
            $toko = Toko::findOrFail($tokoId);

            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|numeric|min:0',
                'deskripsi' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'is_active' => 'nullable|boolean',
                'is_verified' => 'nullable|boolean',
            ]);

            $foto = null;
            $publicId = null;

            if ($request->hasFile('foto')) {
                $upload = $this->uploadImage($request->file('foto'));
                $foto = $upload['url'];
                $publicId = $upload['public_id'];
            }

            Produk::create([
                'user_id' => $toko->user_id, //  penting: ambil dari toko
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'foto' => $foto,
                'image_public_id' => $publicId,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'is_verified' => $request->has('is_verified') ? 1 : 0,
            ]);

            return redirect()
                ->route('admin.toko.show', $tokoId)
                ->with('success', 'Produk berhasil ditambahkan ke toko');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambah produk');
        }
    }
}
