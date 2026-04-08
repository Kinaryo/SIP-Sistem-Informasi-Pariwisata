@extends('admin.layouts.app-admin')

@section('title', $produk->nama_produk)
@section('page-title', 'Manajemen Produk')

@section('content')

    <style>
        .product-img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 16px;
        }

        .price-tag {
            font-size: 26px;
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

        .seller-box {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px 15px;
        }
    </style>

    <div class="row g-4">
        <div class="col-md-12">

            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <h5 class="fw-bold mb-4">Detail Produk</h5>
                <div class="row g-4">

                    <!-- GAMBAR -->
                    <div class="col-md-5 text-center">
                        @if($produk->foto)
                                        <img src="{{ Str::startsWith($produk->foto, 'http')
                            ? $produk->foto
                            : asset('storage/' . $produk->foto) }}" class="product-img">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded-4"
                                style="height:300px; border:2px dashed #dee2e6;">
                                <div class="text-muted text-center">
                                    <i class="bi bi-image fs-1"></i>
                                    <p class="small mb-0">Tidak ada gambar</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- DETAIL -->
                    <div class="col-md-7">

                        <!-- NAMA -->
                        <h3 class="fw-bold mb-2">{{ $produk->nama_produk }}</h3>

                        <!-- HARGA -->
                        <div class="price-tag mb-3">
                            Rp {{ number_format($produk->harga, 0, ',', '.') }}
                        </div>

                        <!-- STATUS -->
                        <div class="mb-3">
                            <span class="badge bg-{{ $produk->is_active ? 'success' : 'secondary' }}">
                                {{ $produk->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>

                            <span class="badge bg-{{ $produk->is_verified ? 'primary' : 'warning' }}">
                                {{ $produk->is_verified ? 'Verified' : 'Belum Verified' }}
                            </span>
                        </div>

                        <!-- SELLER -->
                        <div class="seller-box d-flex align-items-center gap-3 mb-3">

                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                style="width:45px;height:45px;">
                                {{ strtoupper(substr($produk->user->name ?? 'U', 0, 1)) }}
                            </div>

                            <div>
                                <div class="fw-semibold">{{ $produk->user->name ?? '-' }}</div>
                                <small class="text-muted">Pemilik Produk</small>
                            </div>

                        </div>

                        <!-- DESKRIPSI -->
                        <div class="desc-content text-dark" style="line-height:1.8;">
                            {!! $produk->deskripsi ?? '<p class="text-muted">Tidak ada deskripsi</p>' !!}
                        </div>

                        <!-- ACTION -->
                        <div class="mt-4 d-flex flex-wrap gap-2">

                            <a href="javascript:void(0)"
                                onclick="document.referrer ? window.history.back() : window.location.href='{{ route('admin.produks.index') }}'"
                                class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>

                            <!-- EDIT -->
                            <a href="{{ route('admin.produks.edit', $produk->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>

                            <!-- TOGGLE ACTIVE -->
                            <button class="btn {{ $produk->is_active ? 'btn-success' : 'btn-outline-secondary' }}" onclick="confirmToggle(
                            '{{ route('admin.produks.toggleActive', $produk->id) }}',
                            {{ $produk->is_active ? 'true' : 'false' }},
                            'active'
                        )">
                                <i class="bi {{ $produk->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                {{ $produk->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>

                            <!-- TOGGLE VERIFIED -->
                            <button class="btn {{ $produk->is_verified ? 'btn-primary' : 'btn-outline-warning' }}" onclick="confirmToggle(
                            '{{ route('admin.produks.toggleVerified', $produk->id) }}',
                            {{ $produk->is_verified ? 'true' : 'false' }},
                            'verified'
                        )">
                                <i class="bi bi-patch-check"></i>
                                {{ $produk->is_verified ? 'Batalkan Verifikasi' : 'Verifikasi' }}
                            </button>

                            <!-- DELETE -->
                            <button class="btn btn-danger"
                                onclick="handleDelete('{{ route('admin.produks.destroy', $produk->id) }}')">
                                <i class="bi bi-trash"></i> Hapus
                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        // ================= TOGGLE =================
        function confirmToggle(url, currentStatus, type) {

            let text = '';

            if (type === 'active') {
                text = currentStatus
                    ? 'Produk akan dinonaktifkan dari sistem'
                    : 'Produk akan diaktifkan dan tampil di sistem';
            } else {
                text = currentStatus
                    ? 'Status verifikasi akan dibatalkan'
                    : 'Produk akan ditandai sebagai terverifikasi';
            }

            Swal.fire({
                title: 'Konfirmasi Perubahan',
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang memperbarui status produk',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        }
                    })
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Gagal update status', 'error');
                        });
                }

            });
        }


        // ================= DELETE =================
        function handleDelete(url) {
            Swal.fire({
                title: 'Hapus produk?',
                text: 'Data akan dihapus permanen dan tidak bisa dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus data produk',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                        .then(res => res.json())
                        .then(res => {
                            Swal.fire('Berhasil', res.message, 'success')
                                .then(() => window.location.href = "{{ route('admin.produks.index') }}");
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Gagal menghapus produk', 'error');
                        });
                }

            });
        }

    </script>

@endpush