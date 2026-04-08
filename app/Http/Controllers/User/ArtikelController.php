<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class ArtikelController extends Controller
{
    // ================= CLOUDINARY =================
    private function cloudinary()
    {
        return new Cloudinary();
    }

    // ================= UPLOAD IMAGE =================
    private function uploadImage($file, $folder = 'artikel')
    {
        Log::info('Mulai upload gambar ke Cloudinary', [
            'file_name' => $file->getClientOriginalName()
        ]);

        if (!$file || !file_exists($file->getRealPath())) {
            Log::error('File tidak valid saat upload');
            throw new \Exception("File tidak valid atau tidak ditemukan");
        }

        try {
            $upload = $this->cloudinary()->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => $folder,
                ]
            );

            Log::info('Upload Cloudinary berhasil', [
                'url' => $upload['secure_url'],
                'public_id' => $upload['public_id']
            ]);

            return [
                'url' => $upload['secure_url'],
                'public_id' => $upload['public_id'],
            ];
        } catch (\Exception $e) {
            Log::error('Upload Cloudinary gagal', [
                'error' => $e->getMessage()
            ]);

            throw new \Exception("Gagal upload ke Cloudinary: " . $e->getMessage());
        }
    }

    // ================= DELETE IMAGE =================
    private function deleteImage($publicId)
    {
        if (!$publicId) return;

        Log::info('Menghapus gambar dari Cloudinary', [
            'public_id' => $publicId
        ]);

        try {
            $this->cloudinary()->uploadApi()->destroy($publicId);

            Log::info('Berhasil hapus gambar Cloudinary', [
                'public_id' => $publicId
            ]);
        } catch (\Exception $e) {
            Log::error("Gagal hapus gambar Cloudinary", [
                'public_id' => $publicId,
                'error' => $e->getMessage()
            ]);
        }
    }

    // ================= VALIDASI =================
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'judul.required' => 'Judul wajib diisi',
            'judul.max' => 'Judul maksimal 255 karakter',
            'isi.required' => 'Isi artikel wajib diisi',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus jpg, jpeg, atau png',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
        ]);
    }

    // ================= GENERATE SLUG UNIK =================
    private function generateUniqueSlug($judul, $ignoreId = null)
    {
        $slug = Str::slug($judul);
        $original = $slug;
        $counter = 1;

        while (
            Artikel::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // ================= INDEX =================
    public function index(Request $request)
    {
        Log::info('Akses halaman artikel', [
            'search' => $request->search
        ]);

        $search = $request->search;

        $artikels = Artikel::with('user')
            ->activeVerified()

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhere('isi', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->paginate(9)
            ->withQueryString();

        if ($request->ajax()) {
            return view('user.artikel.partials.list', compact('artikels'))->render();
        }

        return view('all.artikel.index', compact('artikels'));
    }

    // ================= SHOW =================
    public function show($slug)
    {
        Log::info('Melihat detail artikel', ['slug' => $slug]);

        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        return view('all.artikel.show', compact('artikel'));
    }

    public function showByOwner($slug)
    {
        Log::info('Owner melihat artikel', ['slug' => $slug]);

        $artikel = Artikel::where('slug', $slug)->firstOrFail();
        return view('dashboard.partials.artikel-showByOwner', compact('artikel'));
    }

    // ================= CREATE =================
    public function create()
    {
        Log::info('Akses halaman create artikel');
        return view('dashboard.partials.artikel-create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        Log::info('Mulai proses simpan artikel', [
            'user_id' => Auth::id(),
            'request' => $request->all()
        ]);

        $this->validateRequest($request);

        try {
            $gambar = null;
            $publicId = null;

            if ($request->hasFile('gambar')) {
                Log::info('File gambar terdeteksi saat store');

                $upload = $this->uploadImage($request->file('gambar'));
                $gambar = $upload['url'];
                $publicId = $upload['public_id'];
            }

            $slug = $this->generateUniqueSlug($request->judul);

            Artikel::create([
                'user_id' => Auth::id(),
                'judul'   => $request->judul,
                'slug'    => $slug,
                'isi'     => $request->isi,
                'gambar'  => $gambar,
                'image_public_id' => $publicId
            ]);

            Log::info('Artikel berhasil disimpan');

            return redirect('/dashboard?tab=artikel')
                ->with('success', 'Artikel berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error("Error store artikel", [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan artikel');
        }
    }

    // ================= EDIT =================
    public function edit($id)
    {
        Log::info('Akses edit artikel', ['id' => $id]);

        $artikel = Artikel::findOrFail($id);
        return view('dashboard.partials.artikel-edit', compact('artikel'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        Log::info('Mulai update artikel', [
            'id' => $id,
            'request' => $request->all()
        ]);

        $artikel = Artikel::findOrFail($id);

        $this->validateRequest($request);

        try {
            $oldPublicId = $artikel->image_public_id;
            $newGambar = $artikel->gambar;
            $newPublicId = $oldPublicId;

            if ($request->hasFile('gambar')) {
                Log::info('Upload gambar baru saat update');

                $upload = $this->uploadImage($request->file('gambar'));

                if ($oldPublicId) {
                    $this->deleteImage($oldPublicId);
                }

                $newGambar = $upload['url'];
                $newPublicId = $upload['public_id'];
            }

            $slug = $this->generateUniqueSlug($request->judul, $artikel->id);

            $artikel->update([
                'judul' => $request->judul,
                'slug'  => $slug,
                'isi'   => $request->isi,
                'gambar' => $newGambar,
                'image_public_id' => $newPublicId
            ]);

            Log::info('Artikel berhasil diupdate', ['id' => $id]);

            return redirect('/dashboard?tab=artikel')
                ->with('success', 'Artikel berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error("Error update artikel", [
                'artikel_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat update artikel');
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        Log::info('Mulai hapus artikel', ['id' => $id]);

        $artikel = Artikel::findOrFail($id);

        try {
            if ($artikel->image_public_id) {
                $this->deleteImage($artikel->image_public_id);
            }

            $artikel->delete();

            Log::info('Artikel berhasil dihapus', ['id' => $id]);

            return redirect('/dashboard?tab=artikel')
                ->with('success', 'Artikel berhasil dihapus');
        } catch (\Exception $e) {
            Log::error("Error delete artikel", [
                'artikel_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Gagal menghapus artikel');
        }
    }


    public function toggleActive($id)
    {
        $artikel = Artikel::findOrFail($id);

        $artikel->is_active = !$artikel->is_active;
        $artikel->save();

        return response()->json([
            'message' => $artikel->is_active
                ? 'Artikel berhasil diaktifkan'
                : 'Artikel berhasil dinonaktifkan'
        ]);
    }


    // // ================= SHOW =================
    // public function show($id)
    // {
    //     try {
    //         $artikel = Artikel::with('user')->findOrFail($id);

    //         // Jika request AJAX → kirim JSON
    //         if (request()->ajax()) {
    //             return response()->json([
    //                 'data' => $artikel
    //             ]);
    //         }

    //         // Jika bukan AJAX → tampilkan halaman
    //         return view('admin.artikel.show', compact('artikel'));
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Artikel tidak ditemukan',
    //             'error' => $e->getMessage()
    //         ], 404);
    //     }
    // }


    // // ================= EDIT =================
    // public function edit($id)
    // {
    //     try {
    //         $artikel = Artikel::findOrFail($id);

    //         // Jika AJAX → kirim JSON (untuk isi modal edit)
    //         if (request()->ajax()) {
    //             return response()->json([
    //                 'data' => $artikel
    //             ]);
    //         }

    //         // Jika biasa → halaman edit
    //         return view('admin.artikel.edit', compact('artikel'));
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Artikel tidak ditemukan',
    //             'error' => $e->getMessage()
    //         ], 404);
    //     }
    // }
}
