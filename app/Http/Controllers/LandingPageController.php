<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\TourismPlace;
use App\Models\Category;
use App\Models\Produk;
use App\Models\Setting;
use App\Models\SiteAccessCount;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $destinations = TourismPlace::with(['category', 'location'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        $produks = Produk::with(['user.toko'])
            ->where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->limit(4)
            ->get();

        $artikels = Artikel::with('user')
            ->where('is_active', true)
            ->where('is_verified', true)
            ->latest()
            ->limit(3)
            ->get();

        $setting = Setting::first();

        $access = SiteAccessCount::first();

        if (!$access) {
            $access = SiteAccessCount::create([
                'total_access' => 0
            ]);
        }

        $userId = auth()->id();
        $ipAddress = $request->ip();

        $visitQuery = Visit::where('visited_at', '>=', now()->subMinutes(30));

        if ($userId) {
            $visitQuery->where('user_id', $userId);
        } else {
            $visitQuery->where('ip_address', $ipAddress);
        }

        $existingVisit = $visitQuery->first();

        if (!$existingVisit) {
            Visit::create([
                'user_id'    => $userId,
                'session_id' => $request->session()->getId(),
                'ip_address' => $ipAddress,
                'user_agent' => $request->userAgent(),
                'path'       => $request->path(),
                'method'     => $request->method(),
                'referer'    => $request->headers->get('referer'),
                'visited_at' => now(),
            ]);
        }

        $todayVisits = Visit::whereDate('visited_at', Carbon::today())->count();

        $weeklyVisits = Visit::whereBetween(
            'visited_at',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]
        )->count();

        $monthlyVisits = Visit::whereMonth('visited_at', Carbon::now()->month)
            ->whereYear('visited_at', Carbon::now()->year)
            ->count();

        $totalVisitRecords = Visit::count();

        $grandTotalVisitors = $access->total_access + $totalVisitRecords;

        $stats = [
            'destinations' => TourismPlace::where('is_active', true)
                ->where('is_verified', true)
                ->count(),

            'visitors' => $grandTotalVisitors,

            'visitors_today' => $todayVisits,

            'visitors_week' => $weeklyVisits,

            'visitors_month' => $monthlyVisits,

            'produks' => Produk::where('is_active', true)
                ->where('is_verified', true)
                ->count(),

            'artikels' => Artikel::where('is_active', true)
                ->where('is_verified', true)
                ->count(),
        ];

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
            ->activeVerified();

        $query->when($request->category, function ($q) use ($request) {
            $q->where('category_id', $request->category);
        });

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
        $setting = Setting::first();

        return view('all.contact', compact('setting'));
    }

    public function show($slug)
    {
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

        $userCity = null;
        $userProvince = null;

        try {

            $ip = request()->ip();

            if (!in_array($ip, ['127.0.0.1', '::1'])) {

                $geo = Cache::remember(
                    'geo_location_' . md5($ip),
                    now()->addDay(),
                    function () use ($ip) {

                        $response = Http::timeout(5)
                            ->get("http://ip-api.com/json/{$ip}");

                        if (!$response->successful()) {
                            return null;
                        }

                        $data = $response->json();

                        if (($data['status'] ?? null) !== 'success') {
                            return null;
                        }

                        return $data;
                    }
                );

                if ($geo) {

                    $userCity = !empty($geo['city'])
                        ? $geo['city']
                        : null;

                    $userProvince = !empty($geo['regionName'])
                        ? $geo['regionName']
                        : null;

                    // fallback jika kota kosong
                    if (!$userCity && $userProvince) {
                        $userCity = $userProvince;
                    }
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $estimasiTiket = rand(1200000, 3500000);

        return view('all.show', compact(
            'tourism_place',
            'userCity',
            'userProvince',
            'estimasiTiket'
        ));
    }
    public function footerStats()
    {
        $access = SiteAccessCount::first();

        if (!$access) {
            $access = SiteAccessCount::create([
                'total_access' => 0
            ]);
        }

        $todayVisits = Visit::whereDate('visited_at', Carbon::today())->count();

        $monthlyVisits = Visit::whereMonth('visited_at', Carbon::now()->month)
            ->whereYear('visited_at', Carbon::now()->year)
            ->count();

        return response()->json([
            'visitors_today' => $todayVisits,
            'visitors_month' => $monthlyVisits,
            'visitors' => $access->total_access + Visit::count(),
        ]);
    }
}
