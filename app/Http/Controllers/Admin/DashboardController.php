<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TourismPlace;
use App\Models\Location;
use App\Models\Category;
use App\Models\SiteAccessCount;
use App\Models\Artikel;
use App\Models\Produk;
use App\Models\Toko;

class DashboardController extends Controller
{
    public function index()
    {
        /* ================= STATISTIK UTAMA ================= */

        $userCount = User::where('role', 'user')->count();
        $adminCount = User::where('role', 'admin')->count();

        $provinceCount = Location::distinct('province')->count('province');
        $categoryCount = Category::count();

        $activeTourismCount = TourismPlace::where('is_active', true)->count();

        $siteAccess = SiteAccessCount::first();
        $totalAccess = $siteAccess->total_access ?? 0;

        /* ================= VERIFIKASI (DIPISAH) ================= */

        $pendingWisata = TourismPlace::where('is_verified', false)->count();
        $pendingArtikel = Artikel::where('is_verified', false)->count();
        $pendingProduk = Produk::where('is_verified', false)->count();

        /* ================= COUNT KONTEN ================= */

        $artikelCount = Artikel::count();
        $produkCount = Produk::count();
        $tokoCount = Toko::count();

        /* ================= DATA LIST ================= */

        $latestArtikel = Artikel::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $latestProduk = Produk::latest()
            ->limit(5)
            ->get();

        $latestToko = Toko::with('user')
            ->latest()
            ->limit(5)
            ->get();

        /* ================= DESTINASI TERPOPULER ================= */

        // fallback aman kalau visitor_count tidak dipakai
        $topDestinations = TourismPlace::withCount('visits')
            ->orderByDesc('visits_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'userCount',
            'adminCount',
            'provinceCount',
            'categoryCount',
            'activeTourismCount',
            'totalAccess',

            // verifikasi
            'pendingWisata',
            'pendingArtikel',
            'pendingProduk',

            // konten
            'artikelCount',
            'produkCount',
            'tokoCount',

            // list
            'latestArtikel',
            'latestProduk',
            'latestToko',
            'topDestinations'
        ));
    }
}