<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class AdminProdukController extends Controller
{
    // ================= CLOUDINARY =================
    private function cloudinary()
    {
        return new Cloudinary();
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
        $query = Produk::with('user')->latest();

        // FILTER ACTIVE
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        // FILTER VERIFIED
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->where('is_verified', 1);
            } elseif ($request->verified === 'no') {
                $query->where('is_verified', 0);
            }
        }

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $produks = $query->paginate(10)->withQueryString();

        return view('admin.produk.index', compact('produks'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.produk.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        try {
            $this->validateRequest($request);

            $foto = null;
            $publicId = null;

            if ($request->hasFile('foto')) {
                $upload = $this->uploadImage($request->file('foto'));
                $foto = $upload['url'];
                $publicId = $upload['public_id'];
            }

            Produk::create([
                'user_id' => auth()->id(),
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'foto' => $foto,
                'image_public_id' => $publicId,

                // STATUS
                'is_active' => $request->has('is_active') ? 1 : 0,
                'is_verified' => $request->has('is_verified') ? 1 : 0,
            ]);

            return redirect()
                ->route('admin.produks.index')
                ->with('success', 'Produk berhasil ditambahkan');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambah produk');
        }
    }

    // ================= SHOW =================
    public function show($id)
    {
        $produk = Produk::with('user')->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);

            $this->validateRequest($request);

            $foto = $produk->foto;
            $publicId = $produk->image_public_id;

            if ($request->hasFile('foto')) {
                $upload = $this->uploadImage($request->file('foto'));

                if ($publicId) {
                    $this->deleteImage($publicId);
                }

                $foto = $upload['url'];
                $publicId = $upload['public_id'];
            }

            $produk->update([
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'foto' => $foto,
                'image_public_id' => $publicId,

                'is_active' => $request->has('is_active') ? 1 : 0,
                'is_verified' => $request->has('is_verified') ? 1 : 0,
            ]);

            return redirect()
                ->route('admin.produks.index')
                ->with('success', 'Produk berhasil diperbarui');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal update produk');
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);

            if ($produk->image_public_id) {
                $this->deleteImage($produk->image_public_id);
            }

            $produk->delete();

            return response()->json([
                'message' => 'Produk berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ================= TOGGLE ACTIVE =================
    public function toggleActive($id)
    {
        $produk = Produk::findOrFail($id);

        $produk->is_active = !$produk->is_active;
        $produk->save();

        return response()->json([
            'message' => 'Status aktif berhasil diubah',
            'status' => $produk->is_active
        ]);
    }

    // ================= TOGGLE VERIFIED =================
    public function toggleVerified($id)
    {
        $produk = Produk::findOrFail($id);

        $produk->is_verified = !$produk->is_verified;
        $produk->save();

        return response()->json([
            'message' => 'Status verifikasi berhasil diubah',
            'status' => $produk->is_verified
        ]);
    }
}