<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class ProdukController extends Controller
{
    // ================= CLOUDINARY =================
    private function cloudinary()
    {
        return new Cloudinary();
    }

    // ================= UPLOAD IMAGE =================
    private function uploadImage($file, $folder = 'produk')
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
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'nama_produk.required' => 'Nama produk wajib diisi',
            'nama_produk.max' => 'Nama produk maksimal 255 karakter',

            'harga.required' => 'Harga wajib diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',

            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format gambar harus jpg, jpeg, atau png',
            'foto.max' => 'Ukuran gambar maksimal 2MB',
        ]);
    }

    // ================= INDEX =================
    public function index(Request $request)
    {
        Log::info('Akses halaman produk', [
            'search' => $request->search
        ]);

        $search = $request->search;

        $produks = Produk::with(['user.toko'])
            ->activeVerified() // ✅ FILTER WAJIB

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_produk', 'like', "%{$search}%")
                        ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            })

            ->latest()
            ->get();

        Log::info('Jumlah produk ditemukan', [
            'total' => $produks->count()
        ]);

        if ($request->ajax()) {
            return view('all.produk.partials.list', compact('produks'))->render();
        }

        return view('all.produk.index', compact('produks'));
    }

    // ================= CREATE =================
    public function create()
    {
        Log::info('Akses halaman create produk');
        return view('dashboard.partials.produk-create');
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        Log::info('Mulai proses simpan produk', [
            'user_id' => Auth::id(),
            'request' => $request->all()
        ]);

        $this->validateRequest($request);

        try {
            $foto = null;
            $publicId = null;

            if ($request->hasFile('foto')) {
                Log::info('File foto terdeteksi saat store');

                $upload = $this->uploadImage($request->file('foto'));
                $foto = $upload['url'];
                $publicId = $upload['public_id'];
            }

            Produk::create([
                'user_id' => Auth::id(),
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'foto' => $foto,
                'image_public_id' => $publicId
            ]);

            Log::info('Produk berhasil disimpan');

            return redirect('/dashboard?tab=produk')
                ->with('success', 'Produk berhasil ditambahkan');
        } catch (\Exception $e) {

            Log::error("Error store produk", [
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan produk');
        }
    }

    // ================= SHOW =================
    public function show($id)
    {
        Log::info('Melihat detail produk', ['id' => $id]);

        $produk = Produk::with('user')->findOrFail($id);
        return view('all.produk.show', compact('produk'));
    }

    public function showByOwner($id)
    {
        Log::info('Owner melihat produk', ['id' => $id]);

        $produk = Produk::with('user')->findOrFail($id);
        return view('dashboard.partials.produk-showByOwner', compact('produk'));
    }

    // ================= EDIT =================
    public function edit($id)
    {
        Log::info('Akses edit produk', ['id' => $id]);

        $produk = Produk::findOrFail($id);
        return view('dashboard.partials.produk-edit', compact('produk'));
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        Log::info('Mulai update produk', [
            'id' => $id,
            'request' => $request->all()
        ]);

        $produk = Produk::findOrFail($id);

        $this->validateRequest($request);

        try {
            $oldPublicId = $produk->image_public_id;
            $newFoto = $produk->foto;
            $newPublicId = $oldPublicId;

            if ($request->hasFile('foto')) {
                Log::info('Upload foto baru saat update');

                $upload = $this->uploadImage($request->file('foto'));

                if ($oldPublicId) {
                    $this->deleteImage($oldPublicId);
                }

                $newFoto = $upload['url'];
                $newPublicId = $upload['public_id'];
            }

            $produk->update([
                'nama_produk' => $request->nama_produk,
                'harga' => $request->harga,
                'deskripsi' => $request->deskripsi,
                'foto' => $newFoto,
                'image_public_id' => $newPublicId
            ]);

            Log::info('Produk berhasil diupdate', ['id' => $id]);

            return redirect('/dashboard?tab=produk')
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {

            Log::error("Error update produk", [
                'produk_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat update produk');
        }
    }

    // ================= DELETE =================
    public function destroy($id)
    {
        Log::info('Mulai hapus produk', ['id' => $id]);

        try {
            $produk = Produk::findOrFail($id);

            if ($produk->image_public_id) {
                $this->deleteImage($produk->image_public_id);
            }

            $produk->delete();

            Log::info('Produk berhasil dihapus', ['id' => $id]);

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Produk berhasil dihapus'
                ]);
            }

            return redirect('/dashboard?tab=produk')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {

            Log::error("Error delete produk", [
                'produk_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus produk'
            ], 500);
        }
    }

    public function toggleActive($id)
    {
        $produk = Produk::where('user_id', auth()->id())->findOrFail($id);

        $produk->is_active = !$produk->is_active;
        $produk->save();

        return response()->json([
            'message' => 'Status produk berhasil diupdate'
        ]);
    }
}
