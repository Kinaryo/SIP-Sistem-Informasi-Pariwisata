@extends('all.layouts.app-all')

@section('title', $produk->nama_produk)

@section('content')

<style>
    .product-img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 12px;
        border: 1px solid #dee2e6;
        transition: 0.3s;
    }

    .product-img:hover {
        transform: scale(1.02);
    }

    .price-tag {
        font-size: 20px;
        font-weight: 700;
        color: #0d6efd;
    }

    .desc-content {
        font-size: 14px;
        line-height: 1.7;
    }

    .desc-content p {
        margin-bottom: 0.8rem;
    }

    .desc-content img {
        max-width: 100%;
        border-radius: 10px;
        margin: 10px 0;
        border: 1px solid #dee2e6;
    }

    .seller-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 13px;
    }

    .product-inner {
        border: 1px solid #e9ecef;
        border-radius: 16px;
        padding: 20px;
    }

    /* ✅ KHUSUS nama produk saja */
    .product-title {
        font-size: 20px;
    }

    .btn {
        font-size: 14px;
        padding: 6px 14px;
    }
</style>

<section class="py-5 bg-light">
    <div class="container">
        <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">

            <!-- HEADER (TETAP BESAR) -->
            <div class="text-center mb-4">
                <h2 class="fw-bold display-6">Detail Produk</h2>
                <p class="text-muted">
                    Lihat informasi lengkap produk unggulan daerah
                </p>
            </div>

            <!-- INNER -->
            <div class="product-inner">
                <div class="row g-4">

                    <!-- GAMBAR -->
                    <div class="col-md-5">
                        @if($produk->foto)
                            <img src="{{ str_starts_with($produk->foto, 'http')
                                ? $produk->foto
                                : asset('storage/' . $produk->foto) }}" 
                                class="product-img">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-3"
                                style="height:300px; border:1px dashed #dee2e6;">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image fs-1 mb-2"></i>
                                    <p class="mb-0 small">Tidak ada gambar</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- DETAIL -->
                    <div class="col-md-7">

                        <!-- Nama Produk (diperkecil) -->
                        <h2 class="fw-bold mb-2 product-title">
                            {{ $produk->nama_produk }}
                        </h2>

                        <!-- Harga -->
                        <div class="price-tag mb-3">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </div>

                        <!-- Seller -->
                        <div class="seller-box d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width:35px; height:35px; font-size:14px;">
                                {{ strtoupper(substr($produk->user->name ?? 'U', 0, 1)) }}
                            </div>

                            <div>
                                <div class="fw-semibold">
                                    {{ $produk->user->name ?? 'User' }}
                                </div>
                                <div class="text-muted small">
                                    Penjual
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="desc-content text-dark mb-4">
                            {!! $produk->deskripsi ?? '<p class="text-muted">Tidak ada deskripsi</p>' !!}
                        </div>

                        <!-- BUTTON -->
                        <div class="d-flex flex-column flex-sm-row gap-2">

                            <a href="https://wa.me/{{ $produk->user->no_whatsapp ?? '0' }}" 
                               target="_blank"
                               class="btn btn-success">
                                <i class="bi bi-whatsapp me-1"></i> Hubungi Penjual
                            </a>

                            <button onclick="history.back()" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </button>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection