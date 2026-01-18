<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TourismPlace;
use App\Models\Location;
use App\Models\Category;
use App\Models\SiteAccessCount; // gunakan tabel site_access_counts
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Jumlah user biasa
        $userCount = User::where('role', 'user')->count();

        // Jumlah admin
        $adminCount = User::where('role', 'admin')->count();

        // Jumlah provinsi unik
        $provinceCount = Location::select('province')->distinct()->count();

        // Jumlah kategori
        $categoryCount = Category::count();

        // Jumlah wisata aktif
        $activeTourismCount = TourismPlace::where('is_active', true)->count();

        // Jumlah wisata menunggu verifikasi
        $pendingVerificationCount = TourismPlace::where('is_verified', false)->count();

        // Total akses situs
        $siteAccess = SiteAccessCount::first();
        $totalAccess = $siteAccess ? $siteAccess->total_access : 0;

        return view('admin.dashboard', compact(
            'userCount',
            'adminCount',
            'provinceCount',
            'categoryCount',
            'activeTourismCount',
            'pendingVerificationCount',
            'totalAccess'
        ));
    }
}
