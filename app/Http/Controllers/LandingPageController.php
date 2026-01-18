<?php

namespace App\Http\Controllers;

use App\Models\TourismPlace;
use App\Models\Category;
use App\Models\Location;
use App\Models\Setting;
use App\Models\SiteAccessCount;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {

        $destinations = TourismPlace::with(['category', 'location'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

      
        $setting = Setting::first();

        $access = SiteAccessCount::first();
        if (!$access) {
            $access = SiteAccessCount::create(['total_access' => 1]);
        } else {
            $access->increment('total_access'); 
            $access->refresh(); 
        }


        $stats = [
            'destinations' => TourismPlace::where('is_verified', true)->count(),
            'provinces'    => Location::select('province')->distinct()->count(),
            'visitors'     => $access->total_access,
            'rating'       => 4.9 // masih dummy rating
        ];


        $categories = Category::all();

        return view('all.landing-page-index', compact(
            'destinations',
            'categories',
            'stats',
            'setting'
        ));
    }

    public function wisata(Request $request)
    {
        // Ambil semua kategori untuk dropdown filter
        $categories = Category::all();

        // Query utama destinasi
        $query = TourismPlace::with(['category', 'location'])
            ->where('is_active', true)
            ->where('is_verified', true);

        // Filter berdasarkan kategori jika ada
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Pencarian berdasarkan nama destinasi
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Ambil hasil terbaru
        $destinations = $query->latest()->get();

        return view('all.wisata', compact('destinations', 'categories'));
    }

    public function about()
    {
        return view('all.about');
    }

    public function contact()
    {
        return view('all.contact');
    }
    public function show($slug)
    {
        // Ambil wisata berdasarkan slug, pastikan aktif & verified
        $tourism_place = TourismPlace::with([
            'category',
            'location',
            'author',
            'facilities',
            'galleries'
        ])->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->firstOrFail();

        return view('all.show', compact('tourism_place'));
    }
}
