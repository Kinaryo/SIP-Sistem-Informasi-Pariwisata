@extends('all.layouts.app-all')

@section('title', 'Detail Wisata')
@section('page-title', 'Detail Destinasi Wisata')

@section('content')
    <style>
        /* ================= HERO ================= */
        .hero-cover {
            position: relative;
            border-radius: 1.25rem;
            overflow: hidden;
        }

        .hero-image {
            height: 550px;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), transparent);
            z-index: 1;
        }

        .hero-content {
            position: absolute;
            bottom: 30px;
            left: 30px;
            color: #fff;
            z-index: 2;
            max-width: 90%;
        }

        /* ================= SIDEBAR ================= */
        .sticky-card {
            position: sticky;
            top: 90px;
        }

        /* ================= GALERI ================= */
        .gallery-wrapper {
            aspect-ratio: 4 / 3;
        }

        .gallery-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: .3s;
            cursor: zoom-in;
        }

        .gallery-wrapper img:hover {
            transform: scale(1.05);
        }

        /* ================= FASILITAS ================= */
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

        /* ================= MODAL ================= */
        .modal-image-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            max-height: 80vh;
        }

        .modal-image-wrapper img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }

        /* ================= MAP ================= */
        #map {
            height: 260px;
        }

        /* ================= RESPONSIVE ================= */
        @media (max-width: 992px) {
            .hero-image {
                height: 400px;
            }

            .sticky-card {
                position: static;
            }
        }

        @media (max-width: 576px) {
            .hero-image {
                height: 260px;
            }

            .hero-content {
                left: 16px;
                right: 16px;
                bottom: 16px;
            }

            .hero-content h2 {
                font-size: 1.3rem;
            }

            #map {
                height: 200px;
            }

            .modal-dialog {
                margin: .5rem;
            }
        }
    </style>

    <div class="container py-5">
        <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Detail Destinasi Wisata</h2>
                <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                <p class="text-muted">
                    Jelajahi destinasi terbaik Indonesia dari alam hingga budaya
                </p>
            </div>

            {{-- HERO --}}
            <div class="hero-cover shadow mb-4 ">
                <div class="hero-image overflow-hidden">
                    <img src="{{ Str::startsWith($tourism_place->cover_image, ['http://', 'https://'])
        ? $tourism_place->cover_image
        : asset('storage/' . $tourism_place->cover_image) }}" class="w-100 h-100 object-fit-cover"
                        onclick="previewImage(this.src)">
                </div>

                <div class="hero-overlay"></div>

                <div class="hero-content">
                    <span class="badge bg-primary mb-2">
                        {{ $tourism_place->category->name ?? 'Wisata' }}
                    </span>
                    <h2 class="fw-bold">{{ $tourism_place->name }}</h2>
                    <p class="mb-0">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        {{ $tourism_place->location->city ?? '-' }},
                        {{ $tourism_place->location->province ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="row g-4">

                {{-- KONTEN --}}
                <div class="col-lg-8">

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
                                                        <div class="col-6 col-md-4">
                                                            <div class="gallery-wrapper border rounded-3 overflow-hidden">
                                                                <img src="{{ Str::startsWith($gallery->image, ['http://', 'https://'])
                                    ? $gallery->image
                                    : asset('storage/' . $gallery->image) }}" onclick="previewImage(this.src)">
                                                            </div>
                                                            <small class="fw-semibold d-block mt-2 text-center">
                                                                {{ $gallery->title }}
                                                            </small>
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

                        <div class="card border-0 shadow rounded-4 mt-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3 text-center">Rencanakan Perjalanan</h5>

                                {{-- HOTEL --}}
                                <a href="https://www.traveloka.com/id-id/hotel/search?query={{ urlencode($tourism_place->location->city) }}"
                                    target="_blank" class="btn btn-success w-100 rounded-pill mb-2">
                                    Hotel di {{ $tourism_place->location->city }}
                                </a>

                                {{-- PESAWAT --}}
                                <a href="https://www.traveloka.com/id-id/flight" target="_blank"
                                    class="btn btn-primary w-100 rounded-pill">
                                    Tiket dari {{ $userCity }} ke {{ $tourism_place->location->city }}
                                </a>
                            </div>

                            {{-- MAP --}}
                            @if ($tourism_place->location?->latitude && $tourism_place->location?->longitude)
                                <div class="card border-0 shadow rounded-4">
                                    <div class="card-body">
                                        <div id="map" class="rounded-3"></div>

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

            {{-- MODAL IMAGE --}}
            <div class="modal fade" id="imagePreviewModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-transparent border-0">
                        <div class="card border-0 shadow-lg rounded-4 position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                                data-bs-dismiss="modal"></button>

                            <div class="card-body p-3 p-md-4">
                                <div class="modal-image-wrapper">
                                    <img id="imagePreview">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                @if ($tourism_place->location?->latitude && $tourism_place->location?->longitude)
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

            function previewImage(src) {
                document.getElementById('imagePreview').src = src;
                new bootstrap.Modal(document.getElementById('imagePreviewModal')).show();
            }
        </script>
    @endpush