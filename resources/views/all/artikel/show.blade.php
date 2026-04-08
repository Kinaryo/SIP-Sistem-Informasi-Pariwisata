@extends('all.layouts.app-all')

@section('title', $artikel->judul)

@section('content')

    <style>
        /* GAMBAR */
        .article-img {
            width: 100%;
            max-height: 75%;
            object-fit: cover;
            border-bottom: 1px solid #dee2e6;
        }

        /* KONTEN ARTIKEL */
        .article-content {
            font-size: 14px;
            line-height: 1.7;
        }

        .article-content p {
            margin-bottom: 0.8rem;
        }

        .article-content img {
            max-width: 100%;
            border-radius: 10px;
            margin: 10px 0;
            border: 1px solid #dee2e6;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3 {
            margin-top: 1.2rem;
            margin-bottom: 0.5rem;
            font-size: 18px;
        }

        .article-content ul {
            padding-left: 20px;
            font-size: 14px;
        }

        .article-content strong {
            font-weight: 600;
        }

        /* JUDUL ARTIKEL (diperkecil saja, header atas tetap besar) */
        .article-title {
            font-size: 20px;
        }

        /* AVATAR */
        .author-avatar {
            width: 35px;
            height: 35px;
            font-size: 14px;
        }

        /* BUTTON */
        .btn {
            font-size: 14px;
            padding: 6px 14px;
        }
    </style>

    <section class="py-5 bg-light">
        <div class="container">

            <!-- CARD -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- HEADER (TETAP BESAR) -->
                <div class="text-center mb-2 mt-4">
                    <h2 class="fw-bold display-6">Detail Artikel</h2>
                    <p class="text-muted">
                        Baca informasi lengkap dan menarik seputar wisata dan daerah
                    </p>
                </div>
                {{-- GAMBAR ATAS --}}
                @if($artikel->gambar)
                        <img src="{{ str_starts_with($artikel->gambar, 'http')
                    ? $artikel->gambar
                    : asset('storage/' . $artikel->gambar) }}" class="article-img">
                @else
                    <div class="d-flex align-items-center justify-content-center bg-light"
                        style="height:75%; border-bottom:2px dashed #dee2e6;">
                        <div class="text-center text-muted">
                            <i class="fas fa-image fa-3x mb-2"></i>
                            <p class="mb-0 small">Tidak ada gambar</p>
                        </div>
                    </div>
                @endif

                {{-- KONTEN --}}
                <div class="p-4 p-md-5">

                    <!-- Judul -->
                    <h2 class="fw-bold mb-3 article-title">
                        {{ $artikel->judul }}
                    </h2>

                    <!-- Penulis -->
                    <div class="d-flex align-items-center gap-2 mb-4">

                        <div
                            class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center author-avatar">
                            {{ strtoupper(substr($artikel->user->name ?? 'A', 0, 1)) }}
                        </div>

                        <div>
                            <div class="fw-semibold small">
                                {{ $artikel->user->name ?? 'Admin' }}
                            </div>
                        </div>

                    </div>

                    <!-- Isi -->
                    <div class="text-dark article-content">
                        {!! $artikel->isi !!}
                    </div>

                    <!-- Tombol -->
                    <div class="mt-4">
                        <button onclick="history.back()" class="btn btn-outline-secondary">
                            ← Kembali
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection