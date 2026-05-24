<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\SiteAccessCount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function index(Request $request)
    {
        /* =====================================================
        | FILTER RANGE (DEFAULT 7 HARI)
        ====================================================== */

        $from = $request->from;
        $to = $request->to;

        if (!$from || !$to) {
            $from = Carbon::now()->subDays(6)->startOfDay();
            $to = Carbon::now()->endOfDay();
        } else {
            $from = Carbon::parse($from)->startOfDay();
            $to = Carbon::parse($to)->endOfDay();
        }

        /* =====================================================
        | BASE QUERY (UNTUK GRAFIK)
        ====================================================== */

        $visitQuery = Visit::whereBetween('visited_at', [$from, $to]);

        /* =====================================================
        | TOTAL VISIT (LEGACY + BARU)
        ====================================================== */

        $legacy = SiteAccessCount::first();
        $legacyTotal = $legacy ? $legacy->total_access : 0;

        $visitTotal = Visit::count();

        $totalVisits = $legacyTotal + $visitTotal;

        /* =====================================================
        | STATISTIK UTAMA
        ====================================================== */

        $todayVisits = Visit::whereDate('visited_at', Carbon::today())->count();

        $weekVisits = Visit::whereBetween('visited_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();

        $monthVisits = Visit::whereMonth('visited_at', Carbon::now()->month)
            ->whereYear('visited_at', Carbon::now()->year)
            ->count();

        /* =====================================================
        | CHART DATA (FILTER RANGE)
        ====================================================== */

        $dailyVisitors = $visitQuery
            ->select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $dailyVisitors->pluck('date');
        $chartData = $dailyVisitors->pluck('total');

        /* =====================================================
        | RETURN VIEW
        ====================================================== */

        return view('admin.visitors.index', compact(
            'totalVisits',
            'todayVisits',
            'weekVisits',
            'monthVisits',
            'chartLabels',
            'chartData',
            'from',
            'to'
        ));
    }
}