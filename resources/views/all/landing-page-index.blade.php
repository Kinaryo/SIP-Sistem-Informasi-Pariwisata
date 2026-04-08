@extends('all.layouts.app-all')

@section('title', 'Landing Page')

@section('content')

    <!-- ================= HERO ================= -->
    <section id="home" class="hero-section d-flex align-items-center text-white text-center" style="min-height:100vh;
                            background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
                                        url('{{ asset('hero.png') }}') center/cover no-repeat;">

        <div class="container">

            <!-- Heading -->
            <h1 class="display-4 fw-bold mb-3">
                Mengenal Merauke Lebih Dekat
            </h1>

            <!-- Subheading -->
            <p class="lead mb-4 mx-auto" style="max-width:700px;">
                Platform informasi digital yang menghadirkan segala hal tentang Merauke
                mulai dari destinasi wisata, budaya, hingga produk lokal dalam satu tempat.
            </p>

            <!-- CTA -->
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/wisata" class="btn btn-primary btn-lg rounded-pill px-5">
                    Jelajahi Sekarang
                </a>

                <a href="/tentang-kami" class="btn btn-outline-light btn-lg rounded-pill px-5">
                    Tentang Kami
                </a>
            </div>

            <!-- Hashtag -->
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

    <!-- ================= STATS ================= -->
    <section class="py-5 bg-white border-bottom">
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

    <!-- ================= TENTANG KAMI ================= -->
    <section id="tentang" class="py-5" style="background:linear-gradient(135deg,#f8f9fa,#eef4ff);">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Tentang Kami</h2>
                <p class="text-muted mt-3">
                    Platform informasi digital tentang Merauke
                </p>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <h5 class="fw-semibold mb-3">Tentang VisitMerauke</h5>
                    <p class="text-muted" style="text-align: justify">
                        VisitMerauke.com adalah platform sistem informasi yang menghadirkan berbagai informasi penting
                        mengenai Merauke, Papua Selatan. Kami tidak hanya berfokus pada pariwisata, tetapi juga
                        memperkenalkan budaya, produk lokal, serta potensi daerah yang dimiliki Merauke.
                    </p>

                    <p class="text-muted" style="text-align: justify">
                        Melalui platform ini, masyarakat dan wisatawan dapat dengan mudah mengakses informasi destinasi,
                        UMKM lokal, artikel, serta berbagai hal menarik yang ada di Merauke dalam satu tempat.
                    </p>

                    <ul class="list-unstyled text-muted mt-3">
                        <li>✔ Informasi destinasi & budaya Merauke</li>
                        <li>✔ Direktori UMKM & produk lokal</li>
                        <li>✔ Artikel & wawasan daerah</li>
                        <li>✔ Mendukung promosi dan digitalisasi Merauke</li>
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
                <h2 class="fw-bold display-6 text-dark">Destinasi Wisata</h2>
                <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                <p class="text-muted">
                    Temukan tempat menarik dan pengalaman unik yang ada di Merauke
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
                                        {{ $item->location->city }},
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

    <!-- ================= PRODUK================= -->
    <section class="py-5 bg-light">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Produk Lokal</h2>
                <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                <p class="text-muted">
                    Jelajahi dan dukung produk UMKM asli Merauke
                </p>
            </div>

            <div class="row g-4">
                @forelse($produks as $p)
                        @php
                            $nama = \Illuminate\Support\Str::limit($p->nama_produk, 40);
                            $toko = $p->user->toko ?? null;
                        @endphp

                        <div class="col-md-6 col-lg-3">
                            <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                                <!-- Gambar -->
                                <a href="{{ route('produk.show', $p->id) }}">
                                    <img src="{{ $p->foto
                    ? (Str::startsWith($p->foto, 'http')
                        ? $p->foto
                        : asset('storage/' . $p->foto))
                    : asset('no-image.png') }}" class="w-100 rounded-top-4" style="height:200px; object-fit:cover;">
                                </a>

                                <div class="card-body d-flex flex-column">

                                    <!-- Nama -->
                                    <h6 class="fw-bold title-limit">
                                        <a href="{{ route('produk.show', $p->id) }}" class="text-dark text-decoration-none">
                                            {{ $nama }}
                                        </a>
                                    </h6>

                                    <!-- Harga -->
                                    <span class="text-primary fw-bold mb-2">
                                        Rp {{ number_format($p->harga, 0, ',', '.') }}
                                    </span>

                                    <!-- Toko -->
                                    <small class="text-muted mb-3">
                                        {{ $toko->nama_toko ?? 'UMKM Lokal' }}
                                    </small>

                                    <!-- Tombol -->
                                    <div class="mt-auto d-flex gap-2">
                                        <a href="{{ route('produk.show', $p->id) }}" class="btn btn-light btn-sm w-100">
                                            Detail
                                        </a>

                                        @php
                                            $toko = $p->user->toko ?? null;
                                            $waAktif = $toko && $toko->telepon && $toko->telepon_aktif;
                                        @endphp

                                        <div class="mt-auto d-flex gap-2">
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
                        </div>

                @empty
                    <p class="text-muted text-center">Belum ada produk.</p>
                @endforelse
                <a href="{{ route('produk.index') }}" class="text-primary fw-semibold">
                    Lihat Semua →
                </a>
            </div>
        </div>
    </section>

    <!-- ================= ARTIKEL ================= -->
    <section class="py-5 bg-white">
        <div class="container">


            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Informasi Dan Artikel Terbaru</h2>
                <div class="mx-auto my-3" style="width:80px;height:4px;background:#0d6efd;"></div>
                <p class="text-muted">
                    Baca berbagai informasi, cerita, dan perkembangan terbaru dari Merauke
                </p>
            </div>

            <div class="row g-4">
                @forelse($artikels as $artikel)
                    @php
                        $isi = \Illuminate\Support\Str::limit(strip_tags($artikel->isi), 100);
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 h-100 card-hover">

                            <!-- Gambar -->
                            @if($artikel->gambar)
                                        <img src="{{ \Illuminate\Support\Str::startsWith($artikel->gambar, 'http')
                                ? $artikel->gambar
                                : asset('storage/' . $artikel->gambar) }}" class="card-img-top rounded-top-4"
                                            style="height:200px; object-fit:cover;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light rounded-top-4"
                                    style="height:200px;">
                                    <small class="text-muted">Tidak ada gambar</small>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">

                                <!-- Judul -->
                                <h6 class="fw-bold title-limit">
                                    {{ $artikel->judul }}
                                </h6>

                                <!-- Info -->
                                <small class="text-muted mb-2">
                                    {{ $artikel->user->name ?? 'Admin' }}
                                </small>

                                <!-- Isi -->
                                <p class="text-muted small flex-grow-1 text-limit">
                                    {{ $isi }}
                                </p>

                                <!-- Tombol -->
                                <a href="{{ route('artikel.show', $artikel->slug) }}"
                                    class="btn btn-outline-primary btn-sm mt-2">
                                    Baca →
                                </a>

                            </div>
                        </div>
                    </div>

                @empty
                    <p class="text-muted text-center">Belum ada artikel.</p>
                @endforelse
                <a href="{{ route('artikel.index') }}" class="text-primary fw-semibold">
                    Lihat Semua →
                </a>
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
                        Hubungi tim VisitMerauke untuk informasi, kerja sama, atau pertanyaan
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

                        <form id="contactForm" action="{{ route('kontak.kirim') }}" method="POST" class="row g-3">
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
                            <iframe
                                src="https://www.google.com/maps?q={{ $setting->latitude ?? '-6.200000' }},{{ $setting->longitude ?? '106.816666' }}&z=15&output=embed"
                                style="border:0;" loading="lazy">
                            </iframe>
                        </div>
                        <small class="text-muted d-block mt-2">
                            {{ $setting->office_name ?? 'Merauke – Papua Selatan' }}
                        </small>
                    </div>
                </div>

            </div>
        </div>
    </section>



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
                document.getElementById('contactForm').reset();
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