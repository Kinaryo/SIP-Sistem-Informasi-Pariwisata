@extends('admin.layouts.app-admin')

@section('title', 'Visitor Statistik')
@section('page-title', 'Visitor Statistik')

@section('content')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="row g-4 mb-2">

        @php
            $visitorCards = [
                [
                    'title' => 'Total Kunjungan',
                    'value' => $totalVisits ?? 0,
                    'icon' => 'bi-bar-chart-line',
                    'color' => 'primary'
                ],
                [
                    'title' => 'Hari Ini',
                    'value' => $todayVisits ?? 0,
                    'icon' => 'bi-calendar-day',
                    'color' => 'success'
                ],
                [
                    'title' => 'Minggu Ini',
                    'value' => $weekVisits ?? 0,
                    'icon' => 'bi-calendar-week',
                    'color' => 'info'
                ],
                [
                    'title' => 'Bulan Ini',
                    'value' => $monthVisits ?? 0,
                    'icon' => 'bi-calendar-month',
                    'color' => 'warning'
                ],
            ];
        @endphp

        @foreach ($visitorCards as $card)
            <div class="col-md-3">

                <div class="card shadow-sm border-0 rounded-4 h-100">

                    <div class="card-body d-flex justify-content-between align-items-center">

                        <div>
                            <small class="text-muted">{{ $card['title'] }}</small>
                            <h3 class="fw-bold mb-0">{{ number_format($card['value']) }}</h3>
                        </div>

                        <div class="bg-{{ $card['color'] }} bg-opacity-10 rounded-circle p-3">
                            <i class="bi {{ $card['icon'] }} fs-3 text-{{ $card['color'] }}"></i>
                        </div>

                    </div>

                </div>

            </div>
        @endforeach

    </div>

    <div class="alert alert-info border-0 shadow-sm rounded mb-2">

        <div class="d-flex">
            <div class="me-3">
                <i class="bi bi-info-circle-fill fs-4"></i>
            </div>

            <div>
                <h6 class="fw-bold mb-1">Catatan</h6>

                <small>
                    Grafik visitor akan otomatis menampilkan 7 hari terakhir jika tidak ada filter tanggal yang dipilih.
                </small>
            </div>
        </div>

    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-12">

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">


                <div class="card-header bg-white border-0 px-4 pt-4 pb-3">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <h5 class="fw-bold mb-0">Grafik Pengunjung</h5>
                            <small class="text-muted">Analisis traffic berdasarkan rentang waktu</small>
                        </div>

                        <form method="GET" class="d-flex gap-2 flex-wrap">

                            <div>
                                <input type="date" name="from" class="form-control form-control-sm"
                                    value="{{ request('from', $from->format('Y-m-d')) }}">
                            </div>

                            <div>
                                <input type="date" name="to" class="form-control form-control-sm"
                                    value="{{ request('to', $to->format('Y-m-d')) }}">
                            </div>

                            {{-- FILTER BUTTON --}}
                            <button class="btn btn-primary btn-sm px-3">
                                <i class="bi bi-funnel"></i>
                                Filter
                            </button>

                            {{-- RESET BUTTON --}}
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset
                            </a>

                        </form>
                    </div>

                </div>


                <div class="card-body pt-3">
                    <canvas id="visitorChart" height="110"></canvas>
                </div>

            </div>

        </div>
    </div>


    <script>
        const ctx = document.getElementById('visitorChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Visitor',
                    data: @json($chartData),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37,99,235,0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                }
            }
        });
    </script>

@endsection