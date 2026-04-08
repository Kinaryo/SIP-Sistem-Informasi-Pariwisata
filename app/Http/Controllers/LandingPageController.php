<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\TourismPlace;
use App\Models\Category;
use App\Models\Location;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\SiteAccessCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LandingPageController extends Controller
{
    public function index()
    {
        // ================= DESTINASI =================
        $destinations = TourismPlace::with(['category', 'location'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        // ================= PRODUK =================
        $produks = Produk::with(['user.toko'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->limit(4)
            ->get();

        // ================= ARTIKEL =================
        $artikels = Artikel::with('user')
            ->where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->limit(3)
            ->get();

        // ================= SETTING =================
        $setting = Setting::first();

        // ================= HITUNG VISITOR =================
        $access = SiteAccessCount::first();

        if (!$access) {
            $access = SiteAccessCount::create(['total_access' => 1]);
        } else {
            $access->increment('total_access');
            $access->refresh();
        }

        // ================= STATS (FIX) =================
        $stats = [
            'destinations' => TourismPlace::where('is_active', true)
                ->where('is_verified', true)
                ->count(),

            'visitors'     => $access->total_access,

            'produks'      => Produk::where('is_active', true)
                ->where('is_verified', true)
                ->count(),

            'artikels'     => Artikel::where('is_active', true)
                ->where('is_verified', true)
                ->count(),
        ];

        // ================= CATEGORY =================
        $categories = Category::all();

        return view('all.landing-page-index', compact(
            'destinations',
            'produks',
            'artikels',
            'categories',
            'stats',
            'setting'
        ));
    }

    public function wisata(Request $request)
    {
        $categories = Category::all();

        $query = TourismPlace::with(['category', 'location'])
            ->activeVerified(); // pakai scope

        // Filter kategori
        $query->when($request->category, function ($q) use ($request) {
            $q->where('category_id', $request->category);
        });

        // Search
        $query->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });

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
        // ================= DATA WISATA =================
        $tourism_place = TourismPlace::with([
            'category',
            'location',
            'author',
            'facilities',
            'galleries'
        ])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('is_verified', true)
            ->firstOrFail();

        // ================= DETEKSI LOKASI USER =================
        $ip = request()->ip();

        $locationData = null;

        // 🔥 SKIP kalau localhost (INI KUNCI NYA)
        if (!in_array($ip, ['127.0.0.1', '::1'])) {
            $locationData = Cache::remember('geoip_' . $ip, 3600, function () use ($ip) {
                return Location::get($ip);
            });
        }

        // ================= DEFAULT =================
        $userCity = 'Kota Anda';
        $userProvince = null;

        if ($locationData) {
            $userCity = $locationData->cityName ?? 'Kota Anda';
            $userProvince = $locationData->regionName ?? null;
        } else {
            // 🔥 fallback kalau localhost / gagal
            $userCity = 'Merauke';
            $userProvince = 'Papua Selatan';
        }

        // ================= ESTIMASI =================
        $estimasiTiket = rand(1200000, 3500000);

        // ================= RETURN =================
        return view('all.show', compact(
            'tourism_place',
            'userCity',
            'userProvince',
            'estimasiTiket'
        ));
    }
}
