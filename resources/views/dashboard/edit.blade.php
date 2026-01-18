@extends('all.layouts.app-all')

@section('title', 'Detail Wisata')
@section('page-title', 'Detail Destinasi Wisata')

@section('content')
@php
    // Helper untuk menampilkan gambar baik dari Cloudinary URL atau local storage
    function imageUrl($image) {
        if (!$image) return asset('images/default.jpg'); // fallback default
        return preg_match('/^https?:\/\//', $image) ? $image : asset('storage/' . $image);
    }
@endphp

<style>
    /* HERO */
    .hero-cover { 
        max-height: 500px; /* dikurangi dari 250px */
        position: relative; 
        overflow: hidden; 
        border-radius: 1.25rem; 
    }
    .hero-cover img { width: 100%; height: 100%; object-fit: cover; }
    .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,.7), transparent); }
    .hero-content { position: absolute; bottom: 15px; left: 15px; color: #fff; }

    /* STICKY SIDEBAR */
    .sticky-card { top: 90px; }
    @media (min-width: 992px) { .sticky-card { position: sticky; } }

    /* GALERI */
    .gallery-img { height: 180px; object-fit: cover; transition: transform .3s; cursor: pointer; }
    .gallery-img:hover { transform: scale(1.05); }

    /* FASILITAS */
    .facility-icon { width: 48px; height: 48px; background: #e7f1ff; color: #0d6efd; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: .5rem; font-size: 20px; }

    /* BORDER DASHED */
    .border-dashed { border: 2px dashed #ced4da !important; }

    /* MAP */
    #map { z-index: 1; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .hero-content { bottom: 10px; left: 10px; }
        .hero-cover { min-height: 150px; } /* dikurangi untuk mobile */
        .gallery-img { height: 140px; }
    }
</style>

<div class="container-fluid py-4 px-3 mt-3 px-md-5">

    {{-- HERO --}}
    <div class="hero-cover shadow mb-4 mt-4">
        @if ($tourism_place->cover_image)
            <img src="{{ imageUrl($tourism_place->cover_image) }}" class="img-fluid">
        @else
            <div class="bg-secondary w-100 h-100"></div>
        @endif

        <div class="hero-overlay"></div>

        {{-- EDIT BUTTON --}}
        <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 m-md-3"
                data-bs-toggle="modal" data-bs-target="#editHeroModal">
            <i class="bi bi-pencil-square"></i> Edit
        </button>

        {{-- HERO CONTENT --}}
        <div class="hero-content">
            <span class="badge bg-primary mb-1">{{ $tourism_place->category->name ?? 'Wisata' }}</span>
            <h2 class="fw-bold fs-5 fs-md-4">{{ $tourism_place->name }}</h2>
            <p class="mb-0 fs-7 fs-md-6">
                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                {{ $tourism_place->location->city ?? '-' }}, {{ $tourism_place->location->province ?? '-' }}
            </p>
        </div>
    </div>

    <div class="row g-4">

        {{-- KONTEN KIRI --}}
        <div class="col-12 col-lg-8">

            {{-- STATUS --}}
            <div class="mb-3 d-flex flex-wrap gap-2">
                @if ($tourism_place->is_verified)
                    <span class="badge bg-success px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i> Terverifikasi
                    </span>
                @endif
                <span class="badge {{ $tourism_place->is_active ? 'bg-info' : 'bg-danger' }} px-3 py-2">
                    {{ $tourism_place->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            {{-- DESKRIPSI --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <h5 class="fw-bold mb-0">Deskripsi Wisata</h5>
                        <button class="btn btn-sm btn-outline-primary mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#editDescriptionModal">
                            <i class="bi bi-pencil"></i> Edit Deskripsi
                        </button>
                    </div>
                    <p class="text-secondary" style="line-height:1.6">{{ $tourism_place->description }}</p>
                </div>
            </div>

            {{-- FASILITAS --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                        <h5 class="fw-bold mb-0">Fasilitas Tersedia</h5>
                        <button class="btn btn-sm btn-outline-primary mt-2 mt-md-0" data-bs-toggle="modal" data-bs-target="#editFacilitiesModal">
                            <i class="bi bi-pencil"></i> Edit Fasilitas
                        </button>
                    </div>

                    @if ($tourism_place->facilities->count())
                        <div class="row g-3 text-center">
                            @foreach ($tourism_place->facilities as $facility)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="border rounded-4 p-3 h-100 d-flex flex-column align-items-center justify-content-center">
                                        <div class="facility-icon"><i class="fas fa-check"></i></div>
                                        <small class="fw-semibold text-center">{{ $facility->name }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Belum ada fasilitas terpilih.</p>
                    @endif
                </div>
            </div>

            {{-- GALERI --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Galeri Foto</h5>
                    <div class="row g-3">
                        @php
                            $totalSlots = 10;
                            $galleries = $tourism_place->galleries;
                        @endphp

                        @for ($i = 0; $i < $totalSlots; $i++)
                            <div class="col-6 col-md-4">
                                <div class="border rounded-3 p-2 h-100 text-center">
                                    @if (isset($galleries[$i]))
                                        @php $gallery = $galleries[$i]; @endphp
                                        <div class="position-relative overflow-hidden rounded-3">
                                            <img src="{{ imageUrl($gallery->image) }}" class="w-100 gallery-img img-fluid">
                                            <div class="position-absolute top-0 end-0 m-1 d-flex gap-1">
                                                <button class="btn btn-sm btn-warning" onclick="openEditGallery({{ $gallery->id }},`{{ $gallery->title }}`)">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $gallery->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <small class="fw-semibold d-block mt-2">{{ $gallery->title }}</small>
                                    @else
                                        <div class="d-flex flex-column justify-content-center align-items-center text-muted h-100 border-dashed py-4"
                                             style="cursor:pointer" onclick="openAddGallery({{ $tourism_place->id }})">
                                            <i class="bi bi-plus-circle fs-1"></i>
                                            <small>Tambah Foto</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

        </div>

        {{-- SIDEBAR --}}
        <div class="col-12 col-lg-4">
            <div class="sticky-card">

                {{-- INFO TIKET --}}
                <div class="card border-0 shadow rounded-4 mb-4">
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Mulai dari</small>
                                <h3 class="fw-bold text-primary fs-6 fs-md-5">Rp {{ number_format($tourism_place->ticket_price, 0, ',', '.') }}</h3>
                            </div>
                            <div class="col-6 d-flex align-items-center">
                                <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                                    <i class="bi bi-pencil"></i> Edit Info
                                </button>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Jam Buka</span>
                            <strong>{{ $tourism_place->open_time }} - {{ $tourism_place->close_time }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Kontak</span>
                            <strong>{{ $tourism_place->contact ?? '-' }}</strong>
                        </div>
                    </div>
                </div>

                {{-- MAP --}}
                @if ($tourism_place->location && $tourism_place->location->latitude && $tourism_place->location->longitude)
                    <div class="card border-0 shadow rounded-4 mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2 flex-wrap">
                                <div class="mb-2">
                                    <i class="bi bi-geo-fill text-danger me-2"></i>
                                    <div class="fw-semibold d-inline">{{ $tourism_place->location->address }}</div>
                                    <small class="d-block text-muted">{{ $tourism_place->location->city }}, {{ $tourism_place->location->province }}</small>
                                </div>
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editLocationModal">
                                    <i class="bi bi-geo-alt"></i> Edit Lokasi
                                </button>
                            </div>
                            <div id="map" style="height:250px" class="rounded-3 mb-3"></div>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ $tourism_place->location->latitude }},{{ $tourism_place->location->longitude }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>

@include('dashboard.partials.modal-edit')

<form id="deleteGalleryForm" method="POST" class="d-none">
    @csrf @method('DELETE')
</form>
@endsection



@push('scripts')
    {{-- Leaflet Maps CSS & JS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Inisialisasi Map
        document.addEventListener('DOMContentLoaded', function () {
            @if ($tourism_place->location && $tourism_place->location->latitude && $tourism_place->location->longitude)
                const lat = {{ $tourism_place->location->latitude }};
                const lng = {{ $tourism_place->location->longitude }};
                const map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                L.marker([lat, lng]).addTo(map)
                    .bindPopup("{{ $tourism_place->name }}")
                    .openPopup();
            @endif
                                            });

        // Script Gallery & Delete
        function openAddGallery(id) {
            document.getElementById('addGalleryForm').action = `/dashboard/gallery/${id}/store`;
            new bootstrap.Modal('#addGalleryModal').show();
        }

        function openEditGallery(id, title) {
            document.getElementById('editGalleryForm').action = `/dashboard/gallery/${id}`;
            document.getElementById('editTitle').value = title;
            new bootstrap.Modal('#editGalleryModal').show();
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus foto?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then(r => {
                if (r.isConfirmed) {
                    let f = document.getElementById('deleteGalleryForm');
                    f.action = `/dashboard/gallery/${id}`;
                    f.submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>


    <script>
        let editMap, marker;

        document.getElementById('editLocationModal')
            .addEventListener('shown.bs.modal', function () {

                const lat = parseFloat(document.getElementById('latInput').value);
                const lng = parseFloat(document.getElementById('lngInput').value);

                editMap = L.map('editMap').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                    .addTo(editMap);

                marker = L.marker([lat, lng], { draggable: true }).addTo(editMap);

                marker.on('dragend', function (e) {
                    const pos = e.target.getLatLng();
                    document.getElementById('latInput').value = pos.lat;
                    document.getElementById('lngInput').value = pos.lng;
                });
            });

    </script>
@endpush