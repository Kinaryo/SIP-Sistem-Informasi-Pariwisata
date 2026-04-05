@extends('all.layouts.app-all')

@section('title', 'Dashboard')

@section('content')

<style>
    /* Header */
    .dashboard-header h2 {
        letter-spacing: 0.5px;
    }

    /* ================= TAB ================= */
    .dashboard-tab {
        background: #f8f9fa;
        padding: 6px;
        border-radius: 50px;
        display: flex;
        flex-wrap: nowrap; /*  penting biar tidak turun */
        width: 100%;
        gap: 6px;
    }

    .dashboard-tab .nav-item {
        flex: 1; /*  bagi rata 3 tab */
    }

    .dashboard-tab .nav-link {
        border-radius: 30px;
        padding: 8px 6px;
        font-size: 13px;
        color: #555;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        transition: all 0.3s ease;
    }

    .dashboard-tab .nav-link i {
        font-size: 15px;
    }

    .dashboard-tab .nav-link:hover {
        background: #e9ecef;
        color: #000;
    }

    .dashboard-tab .nav-link.active {
        background: #0d6efd;
        color: #fff;
        box-shadow: 0 4px 10px rgba(13,110,253,0.3);
    }

    /*  MOBILE OPTIMIZE */
    @media (max-width: 576px) {
        .dashboard-tab .nav-link {
            flex-direction: column;
            gap: 2px;
            font-size: 11px;
            padding: 6px 4px;
        }

        .dashboard-tab .nav-link i {
            font-size: 14px;
        }
    }

    /* Card container */
    .dashboard-container {
        background: #fff;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
</style>

<div class="container py-5">

    <!-- HEADER -->
    <div class="text-center mb-4 dashboard-header">
        <h2 class="fw-bold">Dashboard</h2>
        <p class="text-muted">Kelola data wisata, produk, dan artikel Anda</p>
    </div>

    <!-- TAB MENU -->
    <div class="mb-4">
        <ul class="nav dashboard-tab w-100">

            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'wisata' || !request('tab') ? 'active' : '' }}"
                   href="?tab=wisata">
                    <i class="bi bi-geo-alt"></i>
                    <span>Wisata</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'produk' ? 'active' : '' }}"
                   href="?tab=produk">
                    <i class="bi bi-bag"></i>
                    <span>Produk</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request('tab') == 'artikel' ? 'active' : '' }}"
                   href="?tab=artikel">
                    <i class="bi bi-newspaper"></i>
                    <span>Artikel</span>
                </a>
            </li>

        </ul>
    </div>

    <!-- CONTENT -->
    <div class="dashboard-container">

        @if(request('tab') == 'produk')
            @include('dashboard.partials.produk-index')

        @elseif(request('tab') == 'artikel')
            @include('dashboard.partials.artikel-index')

        @else
            @include('dashboard.partials.wisata-index')
        @endif

    </div>

</div>

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ================= SUCCESS =================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    // ================= ERROR =================
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
        });
    @endif
</script>

@endsection