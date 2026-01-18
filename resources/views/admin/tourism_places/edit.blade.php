@extends('admin.layouts.app-admin')

@section('title', 'Detail Wisata')
@section('page-title', 'Detail Destinasi Wisata')

@section('content')
    <style>
        .hero-cover {
            height: 550px;
            position: relative;
            overflow: hidden;
            border-radius: 1.25rem
        }

        .hero-cover img {
            width: 100%;
            height: 110%;
            object-fit: cover
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .7), transparent)
        }

        .hero-content {
            position: absolute;
            bottom: 30px;
            left: 30px;
            color: #fff
        }

        .sticky-card {
            position: sticky;
            top: 90px
        }

        .gallery-img {
            height: 180px;
            object-fit: cover;
            transition: .3s
        }

        .gallery-img:hover {
            transform: scale(1.05)
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
            font-size: 20px
        }

        .border-dashed {
            border: 2px dashed #ced4da !important
        }

        #map {
            z-index: 1;
        }
    </style>

    <div class="container-fluid mt-2 mb-2 px-2">

        {{-- HERO SECTION --}}
        <div class="hero-cover shadow mb-4">

            {{-- GAMBAR --}}
            @if ($tourism_place->cover_image)
                @php
                    $coverImg = Str::startsWith($tourism_place->cover_image, ['http://', 'https://'])
                        ? $tourism_place->cover_image
                        : asset('storage/' . $tourism_place->cover_image);
                @endphp
                <img src="{{ $coverImg }}">
            @else
                <div class="bg-secondary w-100 h-100"></div>
            @endif

            <div class="hero-overlay"></div>

            {{-- TOMBOL EDIT DI POJOK KANAN --}}
            <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-3" data-bs-toggle="modal"
                data-bs-target="#editHeroModal">
                <i class="bi bi-pencil-square"></i> Edit
            </button>

            {{-- KONTEN HERO (KIRI BAWAH) --}}
            <div class="hero-content">
                <span class="badge bg-primary mb-2">
                    {{ $tourism_place->category->name ?? 'Wisata' }}
                </span>

                <h2 class="fw-bold">{{ $tourism_place->name }}</h2>

                <p class="mb-0">
                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                    {{ $tourism_place->location->city ?? '-' }},
                    {{ $tourism_place->location->province ?? '-' }}
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
                            <i class="fas fa-check-circle me-1"></i>Terverifikasi
                        </span>
                    @endif
                    <span class="badge {{ $tourism_place->is_active ? 'bg-info' : 'bg-danger' }} px-3 py-2">
                        {{ $tourism_place->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>

                {{-- DESKRIPSI --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Deskripsi Wisata</h5>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#editDescriptionModal">
                                <i class="bi bi-pencil"></i> Edit Deskripsi
                            </button>
                        </div>
                        <p class="text-secondary" style="line-height:1.8">
                            {{ $tourism_place->description }}
                        </p>
                    </div>
                </div>

                {{-- FASILITAS --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Fasilitas Tersedia</h5>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#editFacilitiesModal">
                                <i class="bi bi-pencil"></i> Edit Fasilitas
                            </button>
                        </div>
                        @if ($tourism_place->facilities->count())
                            <div class="row g-3 text-center">
                                @foreach ($tourism_place->facilities as $facility)
                                    <div class="col-6 col-md-3">
                                        <div class="border rounded-4 p-3 h-100">
                                            <div class="facility-icon mx-auto"><i class="fas fa-check"></i></div>
                                            <small class="fw-semibold">{{ $facility->name }}</small>
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
                                                @php
                                                    $galleryImg = Str::startsWith($gallery->image, [
                                                        'http://',
                                                        'https://',
                                                    ])
                                                        ? $gallery->image
                                                        : asset('storage/' . $gallery->image);
                                                @endphp
                                                <img src="{{ $galleryImg }}" class="w-100 gallery-img">

                                                <div class="position-absolute top-0 end-0 m-1 d-flex gap-1">
                                                    <button class="btn btn-sm btn-warning"
                                                        onclick="openEditGallery({{ $gallery->id }},`{{ $gallery->title }}`)">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete({{ $gallery->id }})">
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
            <div class="col-lg-4">
                <div class="sticky-card">
                    <div class="card border-0 shadow rounded-4 mb-4">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Mulai dari</small>
                                    <h3 class="fw-bold text-primary">
                                        Rp {{ number_format($tourism_place->ticket_price, 0, ',', '.') }}
                                    </h3>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-outline-primary btn-sm w-100 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#editInfoModal">
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
                            {{-- <button class="btn btn-primary w-100 rounded-pill py-2">Pesan Tiket</button> --}}
                        </div>
                    </div>

                    {{-- MAPS --}}
                    @if ($tourism_place->location && $tourism_place->location->latitude && $tourism_place->location->longitude)
                        <div class="card border-0 shadow rounded-4">
                            <div class="card-body">

                                <div class="row mb-2">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <div class="d-flex align-items-start mb-1">
                                                <i class="bi bi-geo-fill text-danger me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold">
                                                        {{ $tourism_place->location->address }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ $tourism_place->location->city }},
                                                        {{ $tourism_place->location->province }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                            data-bs-target="#editLocationModal">
                                            <i class="bi bi-geo-alt"></i> Edit Lokasi
                                        </button>
                                    </div>
                                </div>

                                {{-- INFO ALAMAT --}}


                                {{-- MAP --}}
                                <div id="map" style="height:250px" class="rounded-3 mb-3"></div>

                                <a href="https://www.google.com/maps/search/?api=1&query={{ $tourism_place->location->latitude }},{{ $tourism_place->location->longitude }}"
                                    target="_blank" class="btn btn-outline-primary btn-sm w-100 rounded-pill">
                                    <i class="bi bi-geo-alt"></i> Lihat di Google Maps
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @include('admin.tourism_places.partials.modal-edit')


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
        document.addEventListener('DOMContentLoaded', function() {
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
            document.getElementById('addGalleryForm').action = `/admin/tourism-places/gallery/${id}/store`;
            new bootstrap.Modal('#addGalleryModal').show();
        }

        function openEditGallery(id, title) {
            document.getElementById('editGalleryForm').action = `/admin/tourism-places/gallery/${id}`;
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
                    f.action = `/admin/tourism-places/gallery/${id}`;
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
            .addEventListener('shown.bs.modal', function() {

                const lat = parseFloat(document.getElementById('latInput').value);
                const lng = parseFloat(document.getElementById('lngInput').value);

                editMap = L.map('editMap').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
                    .addTo(editMap);

                marker = L.marker([lat, lng], {
                    draggable: true
                }).addTo(editMap);

                marker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    document.getElementById('latInput').value = pos.lat;
                    document.getElementById('lngInput').value = pos.lng;
                });
            });
    </script>
@endpush
