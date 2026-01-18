@extends('admin.layouts.app-admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4">

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Total Destinasi</h6>
                <h3 class="fw-bold">{{ $activeTourismCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Kategori</h6>
                <h3 class="fw-bold">{{ $categoryCount ?? 0 }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Provinsi</h6>
                <h3 class="fw-bold">{{ $provinceCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">User</h6>
                <h3 class="fw-bold">{{ $userCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Admin</h6>
                <h3 class="fw-bold">{{ $adminCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Menunggu Verifikasi</h6>
                <h3 class="fw-bold">{{ $pendingVerificationCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Total Akses</h6>
                <h3 class="fw-bold">{{ $totalAccess }}</h3>
            </div>
        </div>
    </div>

</div>
@endsection
