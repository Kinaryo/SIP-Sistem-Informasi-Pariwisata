@extends('all.layouts.app-all')

@section('title', $artikel->judul)

@section('content')

    <style>
        /*  Styling konten artikel */
        .article-content p {
            margin-bottom: 1rem;
        }

        .article-content img {
            max-width: 100%;
            border-radius: 10px;
            margin: 10px 0;
        }

        .article-content h1,
        .article-content h2,
        .article-content h3 {
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .article-content ul {
            padding-left: 20px;
        }

        .article-content strong {
            font-weight: 600;
        }
    </style>

    <section class="py-5 bg-light">
        <div class="container">

            <!--  HEADER -->
            <div class="text-center mb-5">
                <h2 class="fw-bold display-6">Detail Artikel</h2>
                <p class="text-muted">
                    Baca informasi lengkap dan menarik seputar wisata dan daerah
                </p>
            </div>

            <!-- CARD -->
            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

                <div class="row g-4 align-items-start">

                    <!-- KOLOM GAMBAR -->
                    <div class="col-md-5">

                        @if($artikel->gambar)
                            <img src="{{ asset('storage/' . $artikel->gambar) }}" class="img-fluid rounded-4 w-100"
                                style="height:100%; object-fit:cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-4"
                                style="height:250px; border:2px dashed #dee2e6;">

                                <div class="text-center text-muted">
                                    <i class="fas fa-image fa-3x mb-2"></i>
                                    <p class="mb-0 small">Tidak ada gambar</p>
                                </div>
                            </div>
                        @endif

                    </div>

                    <!-- KOLOM KONTEN -->
                    <div class="col-md-7">

                        <!-- Judul -->
                        <h2 class="fw-bold mb-3">
                            {{ $artikel->judul }}
                        </h2>

                        <!--  Penulis + Tanggal -->
                        <div class="d-flex align-items-center gap-2 mb-3">

                            <!-- Avatar -->
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width:40px; height:40px;">
                                {{ strtoupper(substr($artikel->user->name ?? 'A', 0, 1)) }}
                            </div>

                            <!-- Info -->
                            <div>
                                <div class="fw-semibold small">
                                    {{ $artikel->user->name ?? 'Admin' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $artikel->created_at->format('d M Y') }}
                                </div>
                            </div>

                        </div>

                        <!-- Isi Artikel -->
                        <div class="text-dark article-content" style="line-height:1.8;">
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
        </div>
    </section>

@endsection