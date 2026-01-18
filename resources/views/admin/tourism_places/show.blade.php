@extends('admin.layouts.app-admin')

@section('title', 'Detail Wisata')
@section('page-title', 'Detail Destinasi Wisata')

@section('content')
    <style>
        .hero-cover {
            height: 550px;
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem;
        }

        .hero-cover img {
            width: 100%;
            height: 110%;
            object-fit: cover;
            cursor: zoom-in;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), transparent);
        }

        .hero-content {
            position: absolute;
            bottom: 30px;
            left: 30px;
            color: #fff;
        }

        .sticky-card {
            position: sticky;
            top: 90px;
        }

        .gallery-img {
            height: 180px;
            object-fit: cover;
            transition: .3s;
            cursor: zoom-in;
        }

        .gallery-img:hover {
            transform: scale(1.05);
        }

        .facility-icon {
            width: 48px;
            height: 48px;
            background: #e7f1ff;
            color: #0d6efd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: .5rem;
            font-size: 20px;
        }

        /* ===== FIX MODAL IMAGE ===== */
        .modal-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 80vh;
            overflow: hidden;
        }

        .modal-image-wrapper img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }
    </style>

    <div class="container-fluid mt-1 mb-1">

        {{-- HERO --}}
        <div class="hero-cover shadow mb-4">
            @if ($tourism_place->cover_image)
                @php
                    // Jika cover_image URL (Cloudinary), pakai langsung, kalau lokal pakai asset
                    $coverImg = Str::startsWith($tourism_place->cover_image, ['http://', 'https://'])
                        ? $tourism_place->cover_image
                        : asset('storage/' . $tourism_place->cover_image);
                @endphp
                <img src="{{ $coverImg }}" onclick="previewImage(this.src)">
            @else
                <div class="bg-secondary w-100 h-100"></div>
            @endif

            <div class="hero-overlay"></div>

            {{-- BUTTON EDIT --}}
            <a href="{{ route('admin.tourism-places.edit', $tourism_place->slug) }}"
                class="btn btn-warning btn-sm position-absolute top-0 end-0 m-3">
                <i class="bi bi-pencil-square"></i> Edit
            </a>

            <div class="hero-content">
                <span class="badge bg-primary mb-2">{{ $tourism_place->category->name ?? 'Wisata' }}</span>
                <h2 class="fw-bold">{{ $tourism_place->name }}</h2>
                <p class="mb-0">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                    {{ $tourism_place->location->city ?? '-' }}, {{ $tourism_place->location->province ?? '-' }}
                </p>
            </div>
        </div>


        <div class="row g-4">

            {{-- KONTEN KIRI --}}
            <div class="col-lg-8">

                {{-- STATUS --}}
                <div class="mb-3 d-flex gap-2">
                    @if ($tourism_place->is_verified)
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                        </span>
                    @endif
                    <span class="badge {{ $tourism_place->is_active ? 'bg-info' : 'bg-danger' }} px-3 py-2">
                        {{ $tourism_place->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                {{-- DESKRIPSI --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Deskripsi Wisata</h5>
                        <p class="text-secondary" style="line-height:1.8">
                            {{ $tourism_place->description }}
                        </p>
                    </div>
                </div>

                {{-- FASILITAS --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Fasilitas Tersedia</h5>
                        @if ($tourism_place->facilities->count())
                            <div class="row g-3 text-center">
                                @foreach ($tourism_place->facilities as $facility)
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-4 p-3 h-100">
                                            <div class="facility-icon mx-auto">
                                                <i class="bi bi-check-lg"></i>
                                            </div>
                                            <small class="fw-semibold">{{ $facility->name }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada fasilitas.</p>
                        @endif
                    </div>
                </div>

                {{-- GALERI --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Galeri Foto</h5>

                        <div class="row g-3">
                            @forelse ($tourism_place->galleries as $gallery)
                                @php
                                    $img = Str::startsWith($gallery->image, ['http://', 'https://'])
                                        ? $gallery->image
                                        : asset('storage/' . $gallery->image);
                                @endphp
                                <div class="col-6 col-md-4">
                                    <div class="border rounded-3 p-2">
                                        <img src="{{ $img }}" class="w-100 gallery-img rounded-3"
                                            onclick="previewImage(this.src)">
                                        <small class="fw-semibold d-block mt-2 text-center">
                                            {{ $gallery->title }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada foto.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <div class="sticky-card">

                    <div class="card border-0 shadow rounded-4 mb-4">
                        <div class="card-body">
                            <small class="text-muted">Mulai dari</small>
                            <h3 class="fw-bold text-primary">
                                Rp {{ number_format($tourism_place->ticket_price, 0, ',', '.') }}
                            </h3>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Jam Buka</span>
                                <strong>{{ $tourism_place->open_time }} - {{ $tourism_place->close_time }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Kontak</span>
                                <strong>{{ $tourism_place->contact ?? '-' }}</strong>
                            </div>
                        </div>
                    </div>

                    {{-- MAP --}}
                    @if ($tourism_place->location && $tourism_place->location->latitude)
                        <div class="card border-0 shadow rounded-4">
                            <div class="p-2">
                                <div class="d-flex align-items-start mt-2">
                                    <i class="bi bi-geo-fill text-danger me-2"></i>
                                    <div>
                                        <div class="fw-semibold">{{ $tourism_place->location->address }}</div>
                                        <small class="text-muted">
                                            {{ $tourism_place->location->city }},
                                            {{ $tourism_place->location->province }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="map" style="height:250px" class="rounded-3"></div>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $tourism_place->location->latitude }},{{ $tourism_place->location->longitude }}"
                                    target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill mt-3">
                                    <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    {{-- MODAL IMAGE PREVIEW --}}
    <div class="modal fade" id="imagePreviewModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">

                <div class="card border-0 shadow-lg rounded-4 position-relative">

                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal"></button>

                    <div class="card-body p-3 p-md-4">
                        <div class="modal-image-wrapper">
                            <img id="imagePreview" alt="Preview">
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- LEAFLET CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- LEAFLET JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ===== MAP ===== */
            @if ($tourism_place->location && $tourism_place->location->latitude)
                const map = L.map('map').setView([
                            {{ $tourism_place->location->latitude }},
                    {{ $tourism_place->location->longitude }}
                ], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([
                            {{ $tourism_place->location->latitude }},
                    {{ $tourism_place->location->longitude }}
                ]).addTo(map);
            @endif

            });

        /* ===== IMAGE PREVIEW ===== */
        function previewImage(src) {
            const img = document.getElementById('imagePreview');
            img.src = src;

            new bootstrap.Modal(
                document.getElementById('imagePreviewModal')
            ).show();
        }
    </script>
@endpush