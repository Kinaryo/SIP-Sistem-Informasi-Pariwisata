<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class AdminArtikelController extends Controller
{
    // ================= CLOUDINARY =================
    private function cloudinary()
    {
        return new Cloudinary();
    }

    // ================= UPLOAD IMAGE =================
    private function uploadImage($file, $folder = 'artikel')
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
            'judul' => 'required|max:255',
            'isi' => 'required',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'nullable|boolean',
            'is_verified' => 'nullable|boolean',
        ]);
    }

    // ================= SLUG =================
    private function generateSlug($judul, $ignoreId = null)
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $i = 1;

        while (
            Artikel::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }

    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = Artikel::with('user')->latest();

        // ================= FILTER ACTIVE =================
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        // ================= FILTER VERIFIED =================
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->where('is_verified', 1);
            } elseif ($request->verified === 'no') {
                $query->where('is_verified', 0);
            }
        }

        // ================= SEARCH =================
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // ================= PAGINATION =================
        $artikels = $query->paginate(10)->withQueryString();

        return view('admin.artikel.index', compact('artikels'));
    }

    // ================= CREATE =================
    public function create()
    {
        return view('admin.artikel.create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        try {
            $this->validateRequest($request);

            $gambar = null;
            $publicId = null;

            if ($request->hasFile('gambar')) {
                $upload = $this->uploadImage($request->file('gambar'));
                $gambar = $upload['url'];
                $publicId = $upload['public_id'];
            }

            $slug = $this->generateSlug($request->judul);

            Artikel::create([
                'user_id' => auth()->id(),
                'judul' => $request->judul,
                'slug' => $slug,
                'isi' => $request->isi,
                'gambar' => $gambar,
                'image_public_id' => $publicId,

                // 🔥 DEFAULT STATUS
                'is_active' => $request->has('is_active') ? 1 : 0,
                'is_verified' => $request->has('is_verified') ? 1 : 0,
            ]);

            return redirect()
                ->route('admin.articles.index')
                ->with('success', 'Artikel berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah artikel');
        }
    }

    // ================= SHOW =================
    public function show($id)
    {
        $artikel = Artikel::with('user')->findOrFail($id);
        return view('admin.artikel.show', compact('artikel'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.artikel.edit', compact('artikel'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        try {
            $artikel = Artikel::findOrFail($id);
            $this->validateRequest($request);

            $gambar = $artikel->gambar;
            $publicId = $artikel->image_public_id;

            if ($request->hasFile('gambar')) {
                $upload = $this->uploadImage($request->file('gambar'));

                if ($publicId) {
                    $this->deleteImage($publicId);
                }

                $gambar = $upload['url'];
                $publicId = $upload['public_id'];
            }

            $slug = $this->generateSlug($request->judul, $id);

            $artikel->update([
                'judul' => $request->judul,
                'slug' => $slug,
                'isi' => $request->isi,
                'gambar' => $gambar,
                'image_public_id' => $publicId,

                // 🔥 UPDATE STATUS
                'is_active' => $request->has('is_active') ? 1 : 0,
                'is_verified' => $request->has('is_verified') ? 1 : 0,
            ]);

            return redirect()
                ->route('admin.articles.index')
                ->with('success', 'Artikel berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update artikel');
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        try {
            $artikel = Artikel::findOrFail($id);

            if ($artikel->image_public_id) {
                $this->deleteImage($artikel->image_public_id);
            }

            $artikel->delete();

            return response()->json([
                'message' => 'Artikel berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus artikel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ================= TOGGLE ACTIVE =================
    public function toggleActive($id)
    {
        $artikel = Artikel::findOrFail($id);

        $artikel->is_active = !$artikel->is_active;
        $artikel->save();

        return response()->json([
            'message' => 'Status publish berhasil diubah',
            'status' => $artikel->is_active
        ]);
    }

    // ================= TOGGLE VERIFIED =================
    public function toggleVerified($id)
    {
        $artikel = Artikel::findOrFail($id);

        $artikel->is_verified = !$artikel->is_verified;
        $artikel->save();

        return response()->json([
            'message' => 'Status verifikasi berhasil diubah',
            'status' => $artikel->is_verified
        ]);
    }
}
