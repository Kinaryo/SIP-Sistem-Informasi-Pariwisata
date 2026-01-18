@extends('all.layouts.app-all')

@section('title', 'Landing Page')

@section('content')
    <section id="destinasi" class="py-5 bg-white">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Destinasi Wisata Unggulan</h2>
                <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                <p class="text-muted">
                    Jelajahi destinasi terbaik Indonesia dari alam hingga budaya
                </p>
            </div>
            <div class="row mb-4 align-items-center">
                <!-- Filter Kategori -->
                <div class="col-md-6 mb-2 mb-md-0">
                    <form action="{{ route('wisata') }}" method="GET">
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-list text-primary"></i>
                            </span>
                            <select name="category" class="form-select border-start-0" onchange="this.form.submit()">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Pencarian -->
                <div class="col-md-6">
                    <form action="{{ route('wisata') }}" method="GET">
                        <div class="input-group shadow-sm">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Cari destinasi wisata...">
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fas fa-search me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="row g-4">
                @forelse ($destinations as $item)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-lg h-100 rounded-4">

                                <div style="height:260px" class="overflow-hidden">
                                    <img src="{{ Str::startsWith($item->cover_image, ['http://', 'https://'])
                    ? $item->cover_image
                    : asset('storage/' . $item->cover_image) }}" class="w-100 h-100 object-fit-cover">
                                </div>

                                <div class="card-body p-4">
                                    <span class="badge bg-primary mb-2">
                                        {{ $item->category->name }}
                                    </span>

                                    <h5 class="fw-bold">{{ $item->name }}</h5>

                                    <small class="text-muted d-block mb-2">
                                        📍 {{ $item->location->city }},
                                        {{ $item->location->province }}
                                    </small>

                                    <p class="text-muted small">
                                        {{ Str::limit($item->description, 120) }}
                                    </p>

                                    <a href="{{ route('tourism-places.show', $item->slug) }}"
                                        class="fw-semibold text-primary text-decoration-none">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                @empty
                    <p class="text-muted text-center">Belum ada destinasi wisata.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection