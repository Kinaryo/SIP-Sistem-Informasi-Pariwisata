<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Illuminate\Support\Str;

class FacilityController extends Controller
{
    // INDEX - menggunakan view
    public function index()
    {
        $facilities = Facility::all();
        return view('admin.facilities.index', compact('facilities'));
    }

    // SHOW - fetch JSON
    public function show(Facility $facility)
    {
        return response()->json($facility);
    }

    // STORE - fetch JSON
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only('name');

        // ================= SETUP CLOUDINARY =================
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'ddrepuzxq',
                'api_key'    => '736112155155523',
                'api_secret' => 'Di11B6PPvsjHotH1KCfMpHOpbzM',
            ],
            'url' => ['secure' => true],
        ]);

        // ================= UPLOAD IMAGE =================
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'facilities',
                'overwrite' => true,
            ]);
            $data['image'] = $uploaded['secure_url'];
        }

        $facility = Facility::create($data);

        return response()->json([
            'message' => 'Facility created successfully',
            'facility' => $facility
        ]);
    }

    // UPDATE - fetch JSON
    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $facility->name = $request->name;

        // ================= SETUP CLOUDINARY =================
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => 'ddrepuzxq',
                'api_key'    => '736112155155523',
                'api_secret' => 'Di11B6PPvsjHotH1KCfMpHOpbzM',
            ],
            'url' => ['secure' => true],
        ]);

        // ================= UPDATE IMAGE =================
        if ($request->hasFile('image')) {
            // Hapus image lama dari Cloudinary jika berupa URL
            if ($facility->image && Str::startsWith($facility->image, ['http://', 'https://'])) {
                try {
                    $publicId = pathinfo(parse_url($facility->image, PHP_URL_PATH), PATHINFO_FILENAME);
                    $cloudinary->uploadApi()->destroy("facilities/{$publicId}");
                } catch (\Exception $e) {
                    // Bisa log error jika ingin
                }
            } elseif ($facility->image && Storage::disk('public')->exists($facility->image)) {
                Storage::disk('public')->delete($facility->image);
            }

            // Upload image baru ke Cloudinary
            $file = $request->file('image');
            $uploaded = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => 'facilities',
                'overwrite' => true,
            ]);
            $facility->image = $uploaded['secure_url'];
        }

        $facility->save();

        return response()->json([
            'message' => 'Facility updated successfully',
            'facility' => $facility
        ]);
    }

    // DELETE - fetch JSON
    public function destroy(Facility $facility)
    {
        // Hapus image dari Cloudinary atau storage lokal
        if ($facility->image) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => 'ddrepuzxq',
                    'api_key'    => '736112155155523',
                    'api_secret' => 'Di11B6PPvsjHotH1KCfMpHOpbzM',
                ],
                'url' => ['secure' => true],
            ]);

            if (Str::startsWith($facility->image, ['http://', 'https://'])) {
                try {
                    $publicId = pathinfo(parse_url($facility->image, PHP_URL_PATH), PATHINFO_FILENAME);
                    $cloudinary->uploadApi()->destroy("facilities/{$publicId}");
                } catch (\Exception $e) {
                    // bisa log error
                }
            } elseif (Storage::disk('public')->exists($facility->image)) {
                Storage::disk('public')->delete($facility->image);
            }
        }

        $facility->delete();

        return response()->json([
            'message' => 'Facility deleted successfully'
        ]);
    }
}
