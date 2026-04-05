@extends('all.layouts.app-all')

@section('title', $produk->nama_produk)

@section('content')

<style>
    .product-img {
        width: 90%;
        height: 260px;
        object-fit: cover;
        border-radius: 15px;
    }

    .price-tag {
        font-size: 24px;
        font-weight: bold;
        color: #0d6efd;
    }

    .desc-content p {
        margin-bottom: 1rem;
    }

    .desc-content img {
        max-width: 100%;
        border-radius: 10px;
        margin: 10px 0;
    }

    .desc-content ul {
        padding-left: 20px;
    }

    .seller-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 10px 15px;
    }
</style>

<section class="py-5 bg-light">
    <div class="container">

        <!-- HEADER -->
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Detail Produk</h2>
            <p class="text-muted">
                Lihat informasi lengkap produk unggulan daerah
            </p>
        </div>

        <!-- CARD -->
        <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

            <div class="row g-4 align-items-start">

                <!-- GAMBAR -->
                <div class="col-md-5 text-center">

                    @if($produk->foto)
                        <img src="{{ Str::startsWith($produk->foto, 'http')
                            ? $produk->foto
                            : asset('storage/' . $produk->foto) }}" class="product-img">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-4 mx-auto"
                            style="height:260px; width:90%; border:2px dashed #dee2e6;">
                            <div class="text-center text-muted">
                                <i class="bi bi-image fs-1 mb-2"></i>
                                <p class="mb-0 small">Tidak ada gambar</p>
                            </div>
                        </div>
                    @endif

                </div>

                <!-- DETAIL -->
                <div class="col-md-7">

                    <!-- Nama Produk -->
                    <h2 class="fw-bold mb-2">
                        {{ $produk->nama_produk }}
                    </h2>

                    <!-- Harga -->
                    <div class="price-tag mb-3">
                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                    </div>

                    <!-- Seller -->
                    <div class="seller-box d-flex align-items-center gap-3 mb-3">

                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                            style="width:40px; height:40px;">
                            {{ strtoupper(substr($produk->user->name ?? 'U', 0, 1)) }}
                        </div>

                        <div>
                            <div class="fw-semibold small">
                                {{ $produk->user->name ?? 'User' }}
                            </div>
                            <div class="text-muted small">
                                Penjual
                            </div>
                        </div>

                    </div>

                    <!-- Deskripsi -->
                    <div class="desc-content text-dark" style="line-height:1.8;">
                        {!! $produk->deskripsi ?? '<p class="text-muted">Tidak ada deskripsi</p>' !!}
                    </div>

                    <!-- ACTION BUTTON -->
                    <div class="mt-4 d-flex flex-wrap gap-2">

                        <!-- Kembali -->
                        <button onclick="history.back()" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </button>

                        @if(auth()->id() == $produk->user_id)

                            <!-- Edit -->
                            <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <!-- Hapus -->
                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

@endsection