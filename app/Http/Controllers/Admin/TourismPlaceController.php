<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourismPlace;
use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Facility;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;


class TourismPlaceController extends Controller
{
    /* =========================
     * LIST SEMUA WISATA
     * ========================= */
    public function index()
    {
        $places = TourismPlace::with(['category', 'location', 'author'])
            ->latest()
            ->paginate(10);

        return view('admin.tourism_places.index', compact('places'));
    }

    /* =========================
     * LIST WISATA PENDING (USER)
     * ========================= */
    public function pending()
    {
        $places = TourismPlace::with(['category', 'location', 'author'])
            ->where('is_verified', false)
            ->latest()
            ->paginate(10);

        return view('admin.tourism_places.pending', compact('places'));
    }

    /* =========================
     * FORM CREATE (ADMIN)
     * ========================= */
    public function create()
    {
        return view('admin.tourism_places.create', [
            'categories' => Category::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
            'authors'    => User::where('role', 'user')->get(),
        ]);
    }


    public function store(Request $request)
    {
        // ================= VALIDASI =================
        $request->validate([
            'category_id'  => 'required|exists:categories,id',
            'name'         => 'required|string|max:150',
            'description'  => 'required|string',
            'ticket_price' => 'required|integer|min:0',
            'open_time'    => 'required',
            'close_time'   => 'required',
            'contact'      => 'nullable|string',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            'province'     => 'required|string|max:100',
            'city'         => 'required|string|max:100',
            'district'     => 'nullable|string|max:100',
            'address'      => 'nullable|string|max:255',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',

            'gallery.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gallery.*.title' => 'nullable|string|max:150',

            'facilities'   => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        try {
            return DB::transaction(function () use ($request) {

                // ================= CREATE LOCATION =================
                $location = Location::create([
                    'province'  => $request->province,
                    'city'      => $request->city,
                    'district'  => $request->district,
                    'address'   => $request->address,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                ]);

                // ================= SETUP CLOUDINARY =================
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                        'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                        'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
                    ],
                    'url' => ['secure' => true],
                ]);

                // ================= UPLOAD COVER IMAGE =================
                $coverPath = null;
                if ($request->hasFile('cover_image')) {
                    $file = $request->file('cover_image');
                    if ($file && file_exists($file->getRealPath())) {
                        $uploadedCover = $cloudinary->uploadApi()->upload(
                            $file->getRealPath(),
                            [
                                'folder' => 'tourism_places',
                                'overwrite' => true,
                            ]
                        );
                        $coverPath = $uploadedCover['secure_url'];
                    }
                }

                // ================= GENERATE UNIQUE SLUG =================
                $slug = Str::slug($request->name);
                $originalSlug = $slug;
                $count = 1;
                while (TourismPlace::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $count++;
                }

                // ================= CREATE TOURISM PLACE =================
                $tourismPlace = TourismPlace::create([
                    'user_id'      => auth()->id(),
                    'category_id'  => $request->category_id,
                    'location_id'  => $location->id,
                    'name'         => $request->name,
                    'slug'         => $slug,
                    'description'  => $request->description,
                    'ticket_price' => $request->ticket_price,
                    'open_time'    => $request->open_time,
                    'close_time'   => $request->close_time,
                    'contact'      => $request->contact,
                    'cover_image'  => $coverPath,
                    'is_active'    => false, // tetap menunggu aktivasi
                    'is_verified'  => false, // tetap menunggu verifikasi
                ]);

                // ================= SIMPAN FASILITAS =================
                if ($request->filled('facilities')) {
                    $tourismPlace->facilities()->sync($request->facilities);
                }

                // ================= UPLOAD GALERI =================
                foreach ($request->file('gallery.*.image', []) as $index => $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile && file_exists($file->getRealPath())) {
                        $title = $request->gallery[$index]['title'] ?? null;

                        $uploadedGallery = $cloudinary->uploadApi()->upload(
                            $file->getRealPath(),
                            [
                                'folder' => 'tourism_places/gallery',
                                'overwrite' => true,
                            ]
                        );
                        $galleryPath = $uploadedGallery['secure_url'];

                        $tourismPlace->galleries()->create([
                            'image' => $galleryPath,
                            'title' => $title,
                        ]);
                    }
                }

                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Tempat wisata berhasil ditambahkan dan menunggu verifikasi admin.');
            });
        } catch (\Exception $e) {
            Log::error("Error menyimpan tempat wisata: " . $e->getMessage(), [
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan tempat wisata: ' . $e->getMessage());
        }
    }


    public function show($slug)
    {
        $tourism_place = TourismPlace::where('slug', $slug)
            ->with(['author', 'category', 'location', 'galleries', 'facilities', 'reviews'])
            ->firstOrFail();

        return view('admin.tourism_places.show', compact('tourism_place'));
    }



    /* =========================
     * FORM EDIT
     * ========================= */
    public function edit($slug)


    {

        $tourism_place = TourismPlace::where('slug', $slug)
            ->with(['author', 'category', 'location', 'galleries', 'facilities', 'reviews'])
            ->firstOrFail();

        return view('admin.tourism_places.edit', compact('tourism_place'));
    }


    /* =========================
     * UPDATE
     * ========================= */
    public function update(Request $request, $id)
    {
        $tourism_place = TourismPlace::findOrFail($id);

        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'name'        => 'required|string|max:150',
            'description' => 'required|string',
            'ticket_price' => 'required|integer|min:0',
            'open_time'   => 'required',
            'close_time'  => 'required',
        ]);

        if ($tourism_place->name !== $request->name) {
            $tourism_place->slug = $this->generateUniqueSlug(
                $request->name,
                $tourism_place->id
            );
        }

        $tourism_place->update($request->all());

        Log::info('ADMIN UPDATE WISATA', [
            'admin_id' => (int) Auth::id(),
            'wisata_id' => (int) $id
        ]);

        return redirect()
            ->route('admin.tourism-places.index')
            ->with('success', 'Wisata berhasil diperbarui');
    }

    /* =========================
     * VERIFIKASI WISATA USER
     * ========================= */
    public function verify($id)
    {
        $place = TourismPlace::findOrFail($id);

        $place->update([
            'is_verified' => true,
            'is_active'   => true,
        ]);

        Log::notice('ADMIN VERIFIKASI WISATA USER', [
            'admin_id' => (int) Auth::id(),
            'wisata_id' => (int) $id,
            'author_id' => (int) $place->user_id
        ]);

        return redirect()
            ->back()
            ->with('success', 'Wisata berhasil diverifikasi');
    }

    /* =========================
     * TOLAK / NONAKTIFKAN
     * ========================= */
    public function deactivate($id)
    {
        $place = TourismPlace::findOrFail($id);

        $place->update([
            'is_active' => false
        ]);

        Log::warning('ADMIN NONAKTIFKAN WISATA', [
            'admin_id' => (int) Auth::id(),
            'wisata_id' => (int) $id
        ]);

        return redirect()
            ->back()
            ->with('success', 'Wisata berhasil dinonaktifkan');
    }

    public function activate($id)
    {
        $place = TourismPlace::findOrFail($id);

        $place->update([
            'is_active' => true
        ]);

        Log::info('ADMIN AKTIFKAN WISATA', [
            'admin_id' => (int) Auth::id(),
            'wisata_id' => (int) $id
        ]);

        return redirect()
            ->back()
            ->with('success', 'Wisata berhasil diaktifkan');
    }

    /* =========================
     * DELETE
     * ========================= */
    public function destroy($id)
    {
        $place = TourismPlace::findOrFail($id);
        $place->delete();

        Log::alert('ADMIN HAPUS WISATA', [
            'admin_id' => (int) Auth::id(),
            'wisata_id' => (int) $id
        ]);

        return redirect()
            ->route('admin.tourism-places.index')
            ->with('success', 'Wisata berhasil dihapus');
    }

    /* =========================
     * HELPER SLUG AMAN
     * ========================= */
    private function generateUniqueSlug(string $name, $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            TourismPlace::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
















    // ================= STORE NEW GALLERY =================
    public function storeGallery(Request $request, $tourism_place_id)
    {
        $tourism_place = TourismPlace::findOrFail($tourism_place_id);

        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'nullable|string|max:150',
        ]);

        // ================= SETUP CLOUDINARY =================
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
            ],
            'url' => ['secure' => true],
        ]);

        // ================= UPLOAD IMAGE =================
        $uploadedImage = $request->file('image');
        $imageUrl = null;

        if ($uploadedImage && file_exists($uploadedImage->getRealPath())) {
            $uploadResult = $cloudinary->uploadApi()->upload(
                $uploadedImage->getRealPath(),
                [
                    'folder' => 'tourism_places/gallery',
                    'overwrite' => true,
                ]
            );
            $imageUrl = $uploadResult['secure_url'];
        }

        // ================= SIMPAN DI DATABASE =================
        $tourism_place->galleries()->create([
            'image' => $imageUrl,
            'title' => $request->title,
        ]);

        return back()->with('success', 'Foto berhasil ditambahkan ke Cloudinary.');
    }

    // ================= UPDATE GALLERY =================
    public function updateGallery(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'nullable|string|max:150',
        ]);

        // ================= SETUP CLOUDINARY =================
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
            ],
            'url' => ['secure' => true],
        ]);

        // ================= UPDATE IMAGE =================
        if ($request->hasFile('image')) {
            $uploadedImage = $request->file('image');

            // Hapus gambar lama di Cloudinary jika URL valid
            if ($gallery->image && Str::startsWith($gallery->image, ['http://', 'https://'])) {
                try {
                    $publicId = pathinfo(parse_url($gallery->image, PHP_URL_PATH), PATHINFO_FILENAME);
                    $cloudinary->uploadApi()->destroy("tourism_places/gallery/{$publicId}");
                } catch (\Exception $e) {
                    // Jika gagal menghapus lama, lanjutkan saja
                }
            }

            // Upload gambar baru ke Cloudinary
            if ($uploadedImage && file_exists($uploadedImage->getRealPath())) {
                $uploadResult = $cloudinary->uploadApi()->upload(
                    $uploadedImage->getRealPath(),
                    [
                        'folder' => 'tourism_places/gallery',
                        'overwrite' => true,
                    ]
                );
                $gallery->image = $uploadResult['secure_url'];
            }
        }

        $gallery->title = $request->title;
        $gallery->save();

        return back()->with('success', 'Foto berhasil diperbarui di Cloudinary.');
    }

    // ================= DELETE GALLERY =================
    public function deleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);

        // ================= SETUP CLOUDINARY =================
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
            ],
            'url' => ['secure' => true],
        ]);

        // ================= HAPUS IMAGE =================
        if ($gallery->image) {
            if (Str::startsWith($gallery->image, ['http://', 'https://'])) {
                // Hapus dari Cloudinary
                try {
                    $publicId = pathinfo(parse_url($gallery->image, PHP_URL_PATH), PATHINFO_FILENAME);
                    $cloudinary->uploadApi()->destroy("tourism_places/gallery/{$publicId}");
                } catch (\Exception $e) {
                    // Gagal hapus dari Cloudinary, lanjutkan
                }
            } else {
                // Hapus dari storage lokal jika masih ada
                if (Storage::disk('public')->exists($gallery->image)) {
                    Storage::disk('public')->delete($gallery->image);
                }
            }
        }

        $gallery->delete();

        return back()->with('success', 'Foto berhasil dihapus.');
    }
    public function updateHero(Request $request, $id)
    {

        $tourism = TourismPlace::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // ================= SETUP CLOUDINARY =================
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
            ],
            'url' => ['secure' => true],
        ]);

        // ================= UPLOAD COVER IMAGE =================
        if ($request->hasFile('cover_image')) {
            // Hapus cover lama dari Cloudinary jika sebelumnya dari URL
            if ($tourism->cover_image && Str::startsWith($tourism->cover_image, ['http://', 'https://'])) {
                try {
                    $publicId = pathinfo(parse_url($tourism->cover_image, PHP_URL_PATH), PATHINFO_FILENAME);
                    $cloudinary->uploadApi()->destroy("tourism_places/{$publicId}");
                } catch (\Exception $e) {
                }
            } elseif ($tourism->cover_image && Storage::disk('public')->exists($tourism->cover_image)) {
                Storage::disk('public')->delete($tourism->cover_image);
            }

            // Upload cover baru ke Cloudinary
            $file = $request->file('cover_image');
            $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'tourism_places',
                'overwrite' => true,
            ]);
            $tourism->cover_image = $uploaded['secure_url'];
        }

        // ================= UPDATE SLUG =================
        if ($tourism->name !== $request->name) {
            $tourism->slug = $this->generateUniqueSlug($request->name, $tourism->id);
        }

        // ================= UPDATE DATA LAIN =================
        $tourism->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Hero wisata berhasil diperbarui.');
    }

    public function updateDescription(Request $request, $id)
    {
        $tourism = TourismPlace::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'description' => 'required|string',
        ]);

        $tourism->update([
            'description' => $request->description,
        ]);

        return back()->with('success', 'Deskripsi wisata diperbarui.');
    }
    public function updateFacilities(Request $request, $id)
    {
        $tourism = TourismPlace::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        $tourism->facilities()->sync($request->facilities ?? []);

        return back()->with('success', 'Fasilitas berhasil diperbarui.');
    }


    public function updateInfo(Request $request, $id)
    {
        $request->validate([
            'ticket_price' => 'nullable|numeric',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'contact' => 'nullable|string',
        ]);

        TourismPlace::findOrFail($id)->update($request->only([
            'ticket_price',
            'open_time',
            'close_time',
            'contact'
        ]));

        return back()->with('success', 'Info wisata berhasil diperbarui');
    }


    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'province'  => 'required|string|max:100',
            'city'      => 'required|string|max:100',
            'address'   => 'required|string',
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $location = Location::findOrFail(
            TourismPlace::findOrFail($id)->location_id
        );

        $location->update(
            $request->only([
                'province',
                'city',
                'district',
                'address',
                'latitude',
                'longitude',
            ])
        );

        return back()->with('success', 'Lokasi berhasil diperbarui');
    }
}
