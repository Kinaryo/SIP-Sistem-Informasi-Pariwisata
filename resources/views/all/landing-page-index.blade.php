@extends('all.layouts.app-all')

@section('title', 'Landing Page')

@section('content')

    <!-- ================= HERO ================= -->
    <section id="home" class="hero-section d-flex align-items-center text-white text-center" style="min-height:100vh;
                    background: linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
                                url('{{ asset('hero.png') }}') center/cover no-repeat;">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">
                Temukan Keajaiban Indonesia
            </h1>
            <p class="lead mb-5">
                Sistem informasi pariwisata untuk menjelajahi keindahan nusantara.
            </p>
            <a href="#destinasi" class="btn btn-primary btn-lg rounded-pill px-5">
                Mulai Jelajah
            </a>
        </div>
    </section>


    <!-- ================= STATS ================= -->
    <section class="py-5 bg-white border-bottom">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-6 col-md-3">
                    <h3 class="fw-bold text-primary">{{ $stats['destinations'] }}+</h3>
                    <small class="text-muted">DESTINASI</small>
                </div>
                <div class="col-6 col-md-3">
                    <h3 class="fw-bold text-primary">{{ $stats['provinces'] }}</h3>
                    <small class="text-muted">PROVINSI</small>
                </div>
                <div class="col-6 col-md-3">
                    <h3 class="fw-bold text-primary">{{ $stats['visitors'] }}+</h3>
                    <small class="text-muted">WISATAWAN</small>
                </div>
                <div class="col-6 col-md-3">
                    <h3 class="fw-bold text-primary">{{ $stats['rating'] }}</h3>
                    <small class="text-muted">RATING</small>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TENTANG KAMI ================= -->
    <section id="tentang" class="py-5" style="background:linear-gradient(135deg,#f8f9fa,#eef4ff);">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Tentang Kami</h2>
                <p class="text-muted mt-3">
                    Platform informasi pariwisata terpercaya dari wilayah timur Indonesia
                </p>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <h5 class="fw-semibold mb-3">Pesona Wisata Indonesia</h5>
                    <p class="text-muted">
                        Kami adalah platform sistem informasi pariwisata yang bertujuan untuk
                        memperkenalkan keindahan alam, budaya, dan destinasi unggulan Indonesia,
                        khususnya dari wilayah timur seperti Papua dan Maluku.
                    </p>

                    <p class="text-muted">
                        Melalui website ini, kami ingin membantu wisatawan menemukan destinasi terbaik,
                        informasi lokasi, serta gambaran wisata yang akurat dan mudah diakses.
                    </p>

                    <ul class="list-unstyled text-muted mt-3">
                        <li>✔ Informasi destinasi terpercaya</li>
                        <li>✔ Data lokasi & peta wisata</li>
                        <li>✔ Mendukung promosi wisata lokal</li>
                        <li>✔ 100% Gratis</li>
                    </ul>
                </div>

                <div class="col-md-6 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Taman_0_Kilometer_Merauke_-_Sabang.jpg/960px-Taman_0_Kilometer_Merauke_-_Sabang.jpg"
                        class="img-fluid rounded-4 shadow" alt="Tentang Kami">
                </div>
            </div>
        </div>
    </section>

    <!-- ================= DESTINASI ================= -->
    <section id="destinasi" class="py-5 bg-white">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Destinasi Wisata Unggulan</h2>
                <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                <p class="text-muted">
                    Jelajahi destinasi terbaik Indonesia dari alam hingga budaya
                </p>
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

    <!-- ================= KONTAK ================= -->
    <section id="kontak" class="py-5 bg-light">
        <div class="container">

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

                <div class="text-center mb-5">
                    <h2 class="fw-bold display-6">Kontak Kami</h2>
                    <p class="text-muted">
                        Hubungi kami atau kunjungi langsung kantor kami
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Hubungi Kami</h6>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('kontak.kirim') }}" method="POST" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                            </div>

                            <div class="col-12">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="4" placeholder="Pesan"
                                    required></textarea>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>

                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Lokasi Kami</h6>
                        <div class="ratio ratio-4x3 rounded overflow-hidden">
                            {{-- Gunakan latitude & longitude dari $setting --}}
                            <iframe
                                src="https://www.google.com/maps?q={{ $setting->latitude ?? '-6.200000' }},{{ $setting->longitude ?? '106.816666' }}&z=15&output=embed"
                                style="border:0;" loading="lazy">
                            </iframe>
                        </div>
                        <small class="text-muted d-block mt-2">
                            📍 {{ $setting->office_name ?? 'Kantor Pusat' }}
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection