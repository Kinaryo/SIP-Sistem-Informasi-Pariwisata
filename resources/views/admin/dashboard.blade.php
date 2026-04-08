@extends('admin.layouts.app-admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')

{{-- ================= STATISTIK UTAMA ================= --}}
<h5 class="mb-3 fw-bold">Statistik Utama</h5>
<div class="row g-4 mb-4">

    @php
        $mainCards = [
            ['title' => 'Destinasi Aktif', 'value' => $activeTourismCount, 'icon' => 'bi-geo-alt'],
            ['title' => 'Kategori', 'value' => $categoryCount ?? 0, 'icon' => 'bi-tags'],
            ['title' => 'Provinsi', 'value' => $provinceCount, 'icon' => 'bi-map'],
            ['title' => 'Total Akses', 'value' => $totalAccess, 'icon' => 'bi-graph-up'],
        ];
    @endphp

    @foreach ($mainCards as $card)
    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">{{ $card['title'] }}</small>
                    <h4 class="fw-bold mb-0">{{ $card['value'] }}</h4>
                </div>
                <i class="bi {{ $card['icon'] }} fs-3 text-primary"></i>
            </div>
        </div>
    </div>
    @endforeach

</div>


{{-- ================= USER ================= --}}
<h5 class="mb-3 fw-bold">User Management</h5>
<div class="row g-4 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <small class="text-muted">User</small>
                <h4 class="fw-bold">{{ $userCount }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <small class="text-muted">Admin</small>
                <h4 class="fw-bold">{{ $adminCount }}</h4>
            </div>
        </div>
    </div>

</div>


{{-- ================= VERIFIKASI (PENTING) ================= --}}
<h5 class="mb-3 fw-bold text-danger">Menunggu Verifikasi</h5>
<div class="row g-4 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body">
                <small class="text-muted">Wisata</small>
                <h4 class="fw-bold text-danger">{{ $pendingWisata }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body">
                <small class="text-muted">Artikel</small>
                <h4 class="fw-bold text-danger">{{ $pendingArtikel }}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 bg-light">
            <div class="card-body">
                <small class="text-muted">Produk</small>
                <h4 class="fw-bold text-danger">{{ $pendingProduk }}</h4>
            </div>
        </div>
    </div>

</div>


{{-- ================= KONTEN ================= --}}
<h5 class="mb-3 fw-bold">Konten</h5>
<div class="row g-4 mb-4">

    @php
        $contentCards = [
            ['title' => 'Artikel', 'value' => $artikelCount ?? 0, 'icon' => 'bi-file-text'],
            ['title' => 'Produk', 'value' => $produkCount ?? 0, 'icon' => 'bi-box'],
            ['title' => 'Toko', 'value' => $tokoCount ?? 0, 'icon' => 'bi-shop'],
        ];
    @endphp

    @foreach ($contentCards as $card)
    <div class="col-md-4">
        <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">{{ $card['title'] }}</small>
                    <h4 class="fw-bold mb-0">{{ $card['value'] }}</h4>
                </div>
                <i class="bi {{ $card['icon'] }} fs-3 text-success"></i>
            </div>
        </div>
    </div>
    @endforeach

</div>

@endsection