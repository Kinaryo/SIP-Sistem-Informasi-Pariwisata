@extends('all.layouts.app-all')

@section('title', 'Landing Page')

@section('content')

    <style>
        .sponsor-slider {
            overflow: hidden;
            position: relative;
            width: 100%;
            mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, #000 10%, #000 90%, transparent);
        }

        .sponsor-track {
            display: flex;
            align-items: center;
            gap: 40px;
            width: max-content;
            animation: sponsorScroll 20s linear infinite;
        }

        .sponsor-slider:hover .sponsor-track {
            animation-play-state: paused;
        }

        .sponsor-item {
            width: 140px;
            height: 70px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 10px;
            flex-shrink: 0;
        }

        .sponsor-item img {
            max-width: 110px;
            max-height: 50px;
            object-fit: contain;
        }

        .fallback-logo {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border-radius: 50%;
            color: #0d6efd;
            font-size: 1.2rem;
        }

        @keyframes sponsorScroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(calc(-50% - 20px));
            }
        }
    </style>

    <div class="card border-0 rounded-0 shadow-sm mb-4">
        <section id="home" class="hero-section d-flex align-items-center text-white text-center"
            style="min-height:100vh; background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)), url('{{ asset('hero.png') }}') center/cover no-repeat;">
            <div class="container">
                <h1 class="display-4 fw-bold mb-3">
                    Mengenal Merauke Lebih Dekat
                </h1>
                <p class="lead mb-4 mx-auto" style="max-width:700px;">
                    Platform informasi digital yang menghadirkan segala hal tentang Merauke mulai dari destinasi wisata,
                    budaya, hingga produk lokal dalam satu tempat.
                </p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <a href="/wisata" class="btn btn-primary btn-lg rounded-pill px-5">
                        Jelajahi Sekarang
                    </a>
                    <a href="/tentang-kami" class="btn btn-outline-light btn-lg rounded-pill px-5">
                        Tentang Kami
                    </a>
                </div>
                <div class="mt-5 pt-4 d-none d-md-block">
                    <div class="d-flex justify-content-center gap-4 text-white-50 small flex-wrap">
                        <span>#VisitMerauke</span>
                        <span>#ExploreMerauke</span>
                        <span>#PapuaSelatan</span>
                        <span>#WonderfulIndonesia</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-5 mx-3 mx-md-5 bg-white">
        <div class="card-body p-0">
            <section class="py-5 bg-white rounded-4">
                <div class="container">
                    <div class="row text-center g-4">
                        <div class="col-6 col-md-3">
                            <h3 class="fw-bold text-primary">{{ $stats['destinations'] }}+</h3>
                            <small class="text-muted">DESTINASI</small>
                        </div>
                        <div class="col-6 col-md-3">
                            <h3 class="fw-bold text-primary">{{ $stats['produks'] }}+</h3>
                            <small class="text-muted">PRODUK</small>
                        </div>
                        <div class="col-6 col-md-3">
                            <h3 class="fw-bold text-primary">{{ $stats['visitors'] }}+</h3>
                            <small class="text-muted">KUNJUNGAN</small>
                        </div>
                        <div class="col-6 col-md-3">
                            <h3 class="fw-bold text-primary">{{ $stats['artikels'] }}+</h3>
                            <small class="text-muted">ARTIKEL</small>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-5 mx-3 mx-md-5 bg-white">
        <div class="card-body p-0">
            <section id="tentang" class="py-5 rounded-4 bg-white">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-6 text-dark">Tentang Kami</h2>
                        <p class="text-muted mt-3">
                            Platform informasi digital tentang Merauke
                        </p>
                    </div>
                    <div class="row align-items-center g-4 mb-5">
                        <div class="col-md-6">
                            <h5 class="fw-semibold mb-3">Tentang VisitMerauke</h5>
                            <p class="text-muted" style="text-align: justify">
                                visitmerauke.com adalah platform sistem informasi yang menghadirkan berbagai informasi
                                penting mengenai Merauke, Papua Selatan. Kami tidak hanya berfokus pada pariwisata, tetapi
                                juga memperkenalkan budaya, produk lokal, serta potensi daerah yang dimiliki Merauke.
                            </p>
                            <p class="text-muted" style="text-align: justify">
                                Melalui platform ini, masyarakat dan wisatawan dapat dengan mudah mengakses informasi
                                destinasi, UMKM lokal, artikel, serta berbagai hal menarik yang ada di Merauke dalam satu
                                tempat.
                            </p>
                            <ul class="list-unstyled text-muted mt-3">
                                <li>✔ Informasi destinasi & budaya Merauke</li>
                                <li>✔ Direktori UMKM & produk lokal</li>
                                <li>✔ Artikel & wawasan daerah</li>
                                <li>✔ Mendukung promosi dan digitalisasi Merauke</li>
                            </ul>
                        </div>
                        <div class="col-md-6 text-center">
                            <img src="{{ asset('assets/images/tentang-kami.jpg') }}" class="img-fluid rounded-4 shadow-sm"
                                alt="Tentang Kami"
                                onerror="this.style.display='none'; document.getElementById('fallback-icon').style.display='block';">

                            <div id="fallback-icon" style="display:none;">
                                <i class="bi bi-image fs-1 text-secondary"></i>
                                <p class="text-muted mt-2">Gambar tidak tersedia</p>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25 my-5">

                    <div class="mt-4">
                        <div class="text-center mb-4">
                            <h6 class="fw-bold text-dark mb-1 text-uppercase" style="letter-spacing: 1px;">Didukung Oleh
                            </h6>
                        </div>
                        <div class="sponsor-slider">
                            <div class="sponsor-track">
                                @php
                                    $sponsors = [
                                        ['img' => 'sponsor/logo-1.png', 'fallback' => 'bi-building'],
                                        ['img' => 'sponsor/logo-2.png', 'fallback' => 'bi-broadcast'],
                                        ['img' => 'sponsor/logo-3.png', 'fallback' => 'bi-globe2'],
                                    ];
                                @endphp

                                @for ($i = 0; $i < 4; $i++)
                                    @foreach ($sponsors as $sponsor)
                                        <div class="sponsor-item">
                                            <img src="{{ asset($sponsor['img']) }}" alt="Sponsor"
                                                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                            <div class="fallback-logo" style="display:none;"><i
                                                    class="bi {{ $sponsor['fallback'] }}"></i></div>
                                        </div>
                                    @endforeach
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-5 mx-3 mx-md-5 bg-white">
        <div class="card-body p-0">
            <section id="destinasi" class="py-5 bg-white rounded-4">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-6 text-dark">Destinasi Wisata</h2>
                        <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                        <p class="text-muted">
                            Temukan tempat menarik dan pengalaman unik yang ada di Merauke
                        </p>
                    </div>
                    <div class="row g-4 mb-4">
                        @forelse ($destinations as $item)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border bg-light h-100 rounded-4 shadow-sm">
                                    <div style="height:260px" class="overflow-hidden">
                                        <img src="{{ Str::startsWith($item->cover_image, ['http://', 'https://']) ? $item->cover_image : asset('storage/' . $item->cover_image) }}"
                                            class="w-100 h-100 object-fit-cover rounded-top-4" alt="{{ $item->name }}">
                                    </div>
                                    <div class="card-body p-4">
                                        <span class="badge bg-primary mb-2">
                                            {{ $item->category->name }}
                                        </span>
                                        <h5 class="fw-bold text-dark">{{ $item->name }}</h5>
                                        <small class="text-muted d-block mb-2">
                                            {{ $item->location->city }}, {{ $item->location->province }}
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
                            <p class="text-muted text-center w-100">Belum ada destinasi wisata.</p>
                        @endforelse
                    </div>

                    @if($destinations->isNotEmpty())
                        <div class="text-center">
                            <a href="/wisata" class="text-primary fw-semibold text-decoration-none">
                                Lihat Semua →
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-5 mx-3 mx-md-5 bg-white">
        <div class="card-body p-0">
            <section class="py-5 bg-white rounded-4">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-6 text-dark">Produk Lokal</h2>
                        <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                        <p class="text-muted">
                            Jelajahi dan dukung produk UMKM asli Merauke
                        </p>
                    </div>
                    <div class="row g-4 mb-4">
                        @forelse($produks as $p)
                            @php
                                $nama = \Illuminate\Support\Str::limit($p->nama_produk, 40);
                                $toko = $p->user->toko ?? null;
                                $waAktif = $toko && $toko->telepon && $toko->telepon_aktif;
                            @endphp
                            <div class="col-md-6 col-lg-3">
                                <div class="card border bg-light rounded-4 h-100 shadow-sm">
                                    <a href="{{ route('produk.show', $p->id) }}">
                                        <img src="{{ $p->foto ? (Str::startsWith($p->foto, 'http') ? $p->foto : asset('storage/' . $p->foto)) : asset('no-image.png') }}"
                                            class="w-100 rounded-top-4" style="height:200px; object-fit:cover;"
                                            alt="{{ $p->nama_produk }}">
                                    </a>
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="fw-bold">
                                            <a href="{{ route('produk.show', $p->id) }}" class="text-dark text-decoration-none">
                                                {{ $nama }}
                                            </a>
                                        </h6>
                                        <span class="text-primary fw-bold mb-2">
                                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                                        </span>
                                        <small class="text-muted mb-3">
                                            {{ $toko->nama_toko ?? 'UMKM Lokal' }}
                                        </small>
                                        <div class="mt-auto d-flex gap-2">
                                            <a href="{{ route('produk.show', $p->id) }}"
                                                class="btn btn-white btn-sm w-100 border">
                                                Detail
                                            </a>
                                            @if ($waAktif)
                                                <span data-bs-toggle="tooltip" data-bs-title="Hubungi penjual sekarang">
                                                    <a href="https://wa.me/{{ $toko->telepon }}" target="_blank"
                                                        class="btn btn-success btn-sm">
                                                        <i class="bi bi-whatsapp"></i>
                                                    </a>
                                                </span>
                                            @else
                                                <span data-bs-toggle="tooltip" data-bs-title="WhatsApp tidak tersedia / Toko tutup">
                                                    <button class="btn btn-light btn-sm text-muted border" disabled>
                                                        <i class="bi bi-whatsapp"></i>
                                                    </button>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center w-100">Belum ada produk.</p>
                        @endforelse
                    </div>

                    @if($produks->isNotEmpty())
                        <div class="text-center">
                            <a href="{{ route('produk.index') }}" class="text-primary fw-semibold text-decoration-none">
                                Lihat Semua →
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-5 mx-3 mx-md-5 bg-white">
        <div class="card-body p-0">
            <section class="py-5 bg-white rounded-4">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-6 text-dark">Informasi Dan Artikel Terbaru</h2>
                        <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                        <p class="text-muted">
                            Baca berbagai informasi, cerita, dan perkembangan terbaru dari Merauke
                        </p>
                    </div>
                    <div class="row g-4 mb-4">
                        @forelse($artikels as $artikel)
                            @php
                                $isi = \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 100);
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card border bg-light rounded-4 h-100 shadow-sm">
                                    @if($artikel->gambar)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($artikel->gambar, 'http') ? $artikel->gambar : asset('storage/' . $artikel->gambar) }}"
                                            class="card-img-top rounded-top-4" style="height:200px; object-fit:cover;"
                                            alt="{{ $artikel->judul }}">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-white rounded-top-4"
                                            style="height:200px;">
                                            <small class="text-muted">Tidak ada gambar</small>
                                        </div>
                                    @endif
                                    <div class="card-body d-flex flex-column">
                                        <h6 class="fw-bold text-dark">
                                            {{ $artikel->judul }}
                                        </h6>
                                        <small class="text-muted mb-2">
                                            {{ $artikel->user->name ?? 'Admin' }}
                                        </small>
                                        <p class="text-muted small flex-grow-1">
                                            {{ $isi }}
                                        </p>
                                        <a href="{{ route('artikel.show', $artikel->slug) }}"
                                            class="btn btn-outline-primary btn-sm mt-2">
                                            Baca →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center w-100">Belum ada artikel.</p>
                        @endforelse
                    </div>

                    @if($artikels->isNotEmpty())
                        <div class="text-center">
                            <a href="{{ route('artikel.index') }}" class="text-primary fw-semibold text-decoration-none">
                                Lihat Semua →
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>

    <div class="card border-0 rounded-4 shadow-sm mb-5 mx-3 mx-md-5 bg-white">
        <div class="card-body p-0">
            <section id="kontak" class="py-5 bg-white rounded-4">
                <div class="container">
                    <div class="text-center mb-5">
                        <h2 class="fw-bold display-6 text-dark">Kontak Kami</h2>
                        <p class="text-muted">
                            Hubungi tim VisitMerauke untuk informasi, kerja sama, atau pertanyaan
                        </p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-semibold mb-3 text-dark">Hubungi Kami</h6>
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form id="contactForm" action="{{ route('kontak.kirim') }}" method="POST" class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <input type="text" name="name" class="form-control bg-white" placeholder="Nama Lengkap"
                                        required>
                                </div>
                                <div class="col-12">
                                    <input type="email" name="email" class="form-control bg-white" placeholder="Email"
                                        required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" class="form-control bg-white" rows="4" placeholder="Pesan"
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
                            <h6 class="fw-semibold mb-3 text-dark">Lokasi Kami</h6>
                            <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm border bg-white">
                                <iframe
                                    src="https://maps.google.com/maps?q={{ $setting->latitude ?? '0' }},{{ $setting->longitude ?? '0' }}&z=15&output=embed"
                                    style="border:0;" loading="lazy"></iframe>
                            </div>
                            <small class="text-muted d-block mt-2">
                                {{ $setting->office_name ?? 'Merauke – Papua Selatan' }}
                            </small>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (el) {
                return new bootstrap.Tooltip(el)
            })
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('contactForm');
            if (form) {
                form.addEventListener('submit', function () {
                    Swal.fire({
                        title: 'Mengirim pesan...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                });
            }
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0d6efd'
            }).then(() => {
                const contactForm = document.getElementById('contactForm');
                if (contactForm) {
                    contactForm.reset();
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#dc3545'
            });
        </script>
    @endif
@endsection