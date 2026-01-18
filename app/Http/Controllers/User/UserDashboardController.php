<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Location;
use App\Models\TourismPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;


use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

// use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        Log::info("User {$user->id} membuka dashboard.");

        $tourismPlaces = TourismPlace::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        Log::info("User {$user->id} melihat {$tourismPlaces->count()} tempat wisata.");

        return view('dashboard.index', compact('user', 'tourismPlaces'));
    }

    public function createTourismPlaces()
    {
        Log::info("User " . Auth::id() . " membuka form tambah tempat wisata.");
        return view('dashboard.create', [
            'categories' => Category::orderBy('name')->get(),
            'facilities' => Facility::orderBy('name')->get(),
        ]);
    }
    public function storeTourismPlaces(Request $request)
    {
        Log::info("User " . Auth::id() . " mulai menyimpan tempat wisata baru.", $request->all());

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

                Log::info("Mulai transaksi penyimpanan tempat wisata untuk user " . Auth::id());

                // 1. Simpan lokasi
                $location = Location::create([
                    'province'  => $request->province,
                    'city'      => $request->city,
                    'district'  => $request->district,
                    'address'   => $request->address,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                ]);
                Log::info("Lokasi dibuat dengan ID {$location->id}.");

                // 2. Setup Cloudinary manual
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                        'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                        'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
                    ],
                    'url' => ['secure' => true],
                ]);

                // 3. Upload cover image
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
                        Log::info("Cover image diupload ke Cloudinary: {$coverPath}.");
                    }
                }

                // 4. Generate slug unik
                $slug = $this->generateUniqueSlug($request->name);
                Log::info("Slug untuk tempat wisata '{$request->name}' adalah {$slug}.");

                // 5. Simpan tempat wisata
                $tourismPlace = TourismPlace::create([
                    'user_id'      => Auth::id(),
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
                    'is_active'    => false,
                    'is_verified'  => false,
                ]);
                Log::info("Tempat wisata dibuat dengan ID {$tourismPlace->id}.");

                // 6. Simpan fasilitas
                if ($request->filled('facilities')) {
                    $tourismPlace->facilities()->sync($request->facilities);
                    Log::info("Fasilitas disimpan: " . implode(', ', $request->facilities));
                }

                // 7. Upload gallery aman
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

                        Log::info("Gallery image diupload ke Cloudinary: {$galleryPath} dengan judul '{$title}'");
                    }
                }

                Log::info("Selesai menyimpan tempat wisata ID {$tourismPlace->id}, commit transaksi.");
                return redirect()
                    ->route('dashboard')
                    ->with('success', 'Tempat wisata berhasil ditambahkan dan menunggu verifikasi admin.');
            });
        } catch (\Exception $e) {
            Log::error("Error menyimpan tempat wisata: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request' => $request->all()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    private function generateUniqueSlug(string $name, $ignoreId = null): string
    {
        $originalSlug = Str::slug($name);
        $slug = $originalSlug;
        $counter = 1;

        while (
            TourismPlace::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        Log::info("Slug unik dihasilkan: {$slug} untuk nama '{$name}'.");
        return $slug;
    }

    public function showTourismPlaces($slug)
    {
        $tourism_place = TourismPlace::where('slug', $slug)
            ->with(['author', 'category', 'location', 'galleries', 'facilities', 'reviews'])
            ->firstOrFail();

        return view('dashboard.show', compact('tourism_place'));
    }

    public function editTourismPlaces($slug)
    {
        $tourism_place = TourismPlace::where('slug', $slug)
            ->with(['author', 'category', 'location', 'galleries', 'facilities', 'reviews'])
            ->firstOrFail();

        return view('dashboard.edit', compact('tourism_place'));
    }

    // ================= STORE NEW GALLERY =================

    public function storeGallery(Request $request, $tourism_place_id)
    {
        $tourism_place = TourismPlace::findOrFail($tourism_place_id);

        // Validasi
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'nullable|string|max:150',
        ]);

        // Pastikan file benar-benar ada
        $file = $request->file('image');
        if (!$file || !file_exists($file->getRealPath())) {
            return back()->with('error', 'File gambar tidak ditemukan.');
        }

        try {
            // Setup Cloudinary manual
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                    'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
                ],
                'url' => ['secure' => true],
            ]);

            // Upload file ke Cloudinary
            $uploaded = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'tourism_places/gallery',
                    'overwrite' => true,
                ]
            );

            $path = $uploaded['secure_url'];

            // Simpan ke database
            $gallery = $tourism_place->galleries()->create([
                'image' => $path,
                'title' => $request->title,
            ]);

            // Logging
            Log::info("Gallery berhasil diupload ke Cloudinary untuk tourism_place_id={$tourism_place_id}", [
                'image_url' => $path,
                'title' => $request->title,
                'gallery_id' => $gallery->id,
            ]);

            return back()->with('success', 'Foto berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Gagal upload gallery untuk tourism_place_id={$tourism_place_id}: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengupload foto: ' . $e->getMessage());
        }
    }

    // ================= UPDATE GALLERY =================
    public function updateGallery(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'title' => 'nullable|string|max:150',
        ]);

        try {
            // Setup Cloudinary manual
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                    'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
                ],
                'url' => ['secure' => true],
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                if ($file && file_exists($file->getRealPath())) {
                    // Upload image baru ke Cloudinary
                    $uploaded = $cloudinary->uploadApi()->upload(
                        $file->getRealPath(),
                        [
                            'folder' => 'tourism_places/gallery',
                            'overwrite' => true,
                        ]
                    );
                    $gallery->image = $uploaded['secure_url'];

                    Log::info("Gallery ID {$gallery->id} diupdate dengan image baru: {$gallery->image}");
                }
            }

            $gallery->title = $request->title;
            $gallery->save();

            return back()->with('success', 'Foto berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal update gallery ID {$gallery->id}: " . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui foto: ' . $e->getMessage());
        }
    }

    // ================= DELETE GALLERY =================

    public function deleteGallery($id)
    {
        $gallery = Gallery::findOrFail($id);

        try {
            // Setup Cloudinary manual
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                    'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
                ],
                'url' => ['secure' => true],
            ]);

            // Hapus file dari Cloudinary jika ada
            if ($gallery->image) {
                // Ambil public_id dari URL Cloudinary
                $parsed = parse_url($gallery->image);
                $path = $parsed['path'] ?? '';
                $publicId = trim($path, '/'); // hapus slash di awal
                $publicId = preg_replace('/\.[^.]+$/', '', $publicId); // hapus ekstensi

                // Hapus dari Cloudinary
                $cloudinary->uploadApi()->destroy($publicId, ['resource_type' => 'image']);

                Log::info("Gallery ID {$gallery->id} berhasil dihapus dari Cloudinary: {$gallery->image}");
            }

            $gallery->delete();

            return back()->with('success', 'Foto berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus gallery ID {$gallery->id}: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus foto: ' . $e->getMessage());
        }
    }



    public function updateHero(Request $request, $id)
    {
        $tourism = TourismPlace::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            // Setup Cloudinary manual
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME', 'ddrepuzxq'),
                    'api_key'    => env('CLOUDINARY_API_KEY', '736112155155523'),
                    'api_secret' => env('CLOUDINARY_API_SECRET', 'Di11B6PPvsjHotH1KCfMpHOpbzM'),
                ],
                'url' => ['secure' => true],
            ]);

            // Upload cover image baru jika ada
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');

                // Hapus cover lama dari Cloudinary jika ada
                if ($tourism->cover_image) {
                    $parsed = parse_url($tourism->cover_image);
                    $path = $parsed['path'] ?? '';
                    $publicId = trim($path, '/');
                    $publicId = preg_replace('/\.[^.]+$/', '', $publicId);

                    $cloudinary->uploadApi()->destroy($publicId, ['resource_type' => 'image']);
                    Log::info("Cover lama dihapus dari Cloudinary: {$tourism->cover_image}");
                }

                // Upload cover baru
                $uploaded = $cloudinary->uploadApi()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'tourism_places',
                        'overwrite' => true,
                    ]
                );

                $tourism->cover_image = $uploaded['secure_url'];
                Log::info("Cover baru diupload ke Cloudinary: {$tourism->cover_image}");
            }

            // Update slug jika nama berubah
            if ($tourism->name !== $request->name) {
                $tourism->slug = $this->generateUniqueSlug($request->name, $tourism->id);
            }

            $tourism->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
            ]);

            return back()->with('success', 'Hero wisata berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal update hero wisata ID {$tourism->id}: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat memperbarui hero wisata: ' . $e->getMessage());
        }
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


    public function updateDetail(Request $request, $id)
    {
        $tourism = TourismPlace::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('location')
            ->firstOrFail();

        $request->validate([
            'description'   => 'required|string',
            'ticket_price'  => 'required|integer|min:0',
            'open_time'     => 'required',
            'close_time'    => 'required',

            'facilities'    => 'nullable|array',
            'facilities.*'  => 'exists:facilities,id',

            'latitude'      => 'nullable|numeric',
            'longitude'     => 'nullable|numeric',
            'address'       => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // ================= UPDATE TOURISM PLACE =================
            $tourism->update([
                'description'   => $request->description,
                'ticket_price'  => $request->ticket_price,
                'open_time'     => $request->open_time,
                'close_time'    => $request->close_time,
            ]);

            // ================= UPDATE FASILITAS =================
            $tourism->facilities()->sync($request->facilities ?? []);

            // ================= UPDATE / CREATE LOCATION =================
            Location::updateOrCreate(
                ['id' => $tourism->location_id],
                [
                    'address'   => $request->address,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                ]
            );

            DB::commit();

            Log::info("Detail wisata ID {$tourism->id} berhasil diperbarui oleh user " . Auth::id());

            return back()->with('success', 'Detail wisata berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Gagal update detail wisata", [
                'tourism_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui detail wisata.');
        }
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
