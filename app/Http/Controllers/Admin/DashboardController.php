<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TourismPlace;
use App\Models\Location;
use App\Models\Category;
use App\Models\Artikel;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Visit;
use App\Models\SiteAccessCount;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $userCount = User::where('role', 'user')->count();

        $adminCount = User::where('role', 'admin')->count();

        $provinceCount = Location::distinct('province')
            ->count('province');

        $categoryCount = Category::count();

        $activeTourismCount = TourismPlace::where('is_active', true)
            ->count();

        $access = SiteAccessCount::first();

        if (!$access) {
            $access = SiteAccessCount::create([
                'total_access' => 0
            ]);
        }

        $totalVisitRecords = Visit::count();

        $totalAccess = $access->total_access + $totalVisitRecords;

        $pendingWisata = TourismPlace::where('is_verified', false)
            ->count();

        $pendingArtikel = Artikel::where('is_verified', false)
            ->count();

        $pendingProduk = Produk::where('is_verified', false)
            ->count();


        $artikelCount = Artikel::count();

        $produkCount = Produk::count();

        $tokoCount = Toko::count();

        /* =====================================================
        | DATA TERBARU
        ====================================================== */

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

        /* =====================================================
        | VISITOR STATISTICS
        ====================================================== */

        $todayVisitors = Visit::whereDate(
            'visited_at',
            Carbon::today()
        )->count();

        $weekVisitors = Visit::whereBetween(
            'visited_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]
        )->count();

        $monthVisitors = Visit::whereMonth(
                'visited_at',
                Carbon::now()->month
            )
            ->whereYear(
                'visited_at',
                Carbon::now()->year
            )
            ->count();

        $totalVisitors = Visit::count();

        $uniqueVisitors = Visit::distinct('ip_address')
            ->count('ip_address');

        /* =====================================================
        | PAGE TERPOPULER
        ====================================================== */

        $popularPages = Visit::select(
                'path',
                DB::raw('COUNT(*) as total_visits')
            )
            ->groupBy('path')
            ->orderByDesc('total_visits')
            ->limit(10)
            ->get();

        /* =====================================================
        | DESTINASI TERPOPULER
        ====================================================== */

        $topDestinations = Visit::select(
                'path',
                DB::raw('COUNT(*) as total_visits')
            )
            ->where('path', 'like', '%wisata%')
            ->groupBy('path')
            ->orderByDesc('total_visits')
            ->limit(5)
            ->get();

        $weeklyVisitors = Visit::select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate(
                'visited_at',
                '>=',
                now()->subDays(6)
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard', compact(

            /* statistik utama */
            'userCount',
            'adminCount',
            'provinceCount',
            'categoryCount',
            'activeTourismCount',
            'totalAccess',

            /* verifikasi */
            'pendingWisata',
            'pendingArtikel',
            'pendingProduk',

            /* total konten */
            'artikelCount',
            'produkCount',
            'tokoCount',

            /* latest */
            'latestArtikel',
            'latestProduk',
            'latestToko',

            /* visitor */
            'todayVisitors',
            'weekVisitors',
            'monthVisitors',
            'totalVisitors',
            'uniqueVisitors',

            /* analytics */
            'popularPages',
            'topDestinations',
            'weeklyVisitors'
        ));
    }
}