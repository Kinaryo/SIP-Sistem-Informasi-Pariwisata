@extends('admin.layouts.app-admin')

@section('title', 'Detail Toko')
@section('page-title', 'Manajemen Toko')

@section('content')

    <style>
        .logo-toko {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
        }
    </style>

    <div class="row">
        <div class="col-md-12 mx-auto">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <!-- KIRI -->
                    <div>
                        <h5 class="fw-bold mb-1">Detail Toko</h5>
                        <small class="text-muted">Informasi lengkap toko pengguna</small>
                    </div>

                    <!-- KANAN -->
                    <div class="d-flex flex-wrap gap-2">

                        <a href="{{ route('admin.toko.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>

                        <a href="{{ route('admin.toko.edit', $toko->id) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>

                        <button class="btn {{ $toko->telepon_aktif ? 'btn-success' : 'btn-secondary' }}" onclick="toggleTelepon(
                    '{{ route('admin.toko.toggleTelepon', $toko->id) }}',
                    {{ $toko->telepon_aktif ? 'true' : 'false' }}
                )">
                            <i class="bi bi-telephone"></i>
                            {{ $toko->telepon_aktif ? 'Nonaktifkan Telepon' : 'Aktifkan Telepon' }}
                        </button>

                        <button class="btn btn-danger"
                            onclick="handleDelete('{{ route('admin.toko.destroy', $toko->id) }}')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>

                    </div>

                </div>

                <div class="row g-4">

                    <!-- LOGO -->
                    <div class="col-md-6 text-center">

                        @if($toko->logo)
                            <img src="{{ $toko->logo }}" class="logo-toko mb-3">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center mx-auto logo-toko">
                                <i class="bi bi-shop fs-1 text-muted"></i>
                            </div>
                        @endif

                        <!-- STATUS -->
                        <div class="mt-3">
                            <span class="badge px-4 py-3 bg-{{ $toko->telepon_aktif ? 'success' : 'secondary' }}">
                                {{ $toko->telepon_aktif ? 'Telepon Aktif' : 'Telepon Nonaktif' }}
                            </span>
                        </div>

                    </div>

                    <!-- DETAIL -->
                    <div class="col-md-6">

                        <table class="table table-borderless">

                            <tr>
                                <th width="200">Nama Toko</th>
                                <td>: {{ $toko->nama_toko }}</td>
                            </tr>

                            <tr>
                                <th>Slug</th>
                                <td>: {{ $toko->slug }}</td>
                            </tr>

                            <tr>
                                <th>Owner</th>
                                <td>: {{ $toko->user->name ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>: {{ $toko->user->email ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Telepon</th>
                                <td>: {{ $toko->telepon ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Dibuat</th>
                                <td>: {{ $toko->created_at?->format('d M Y H:i') }}</td>
                            </tr>

                        </table>

                    </div>

                </div>

                <!-- DESKRIPSI -->
                <div class="mt-4">
                    <h6 class="fw-bold">Deskripsi</h6>
                    <div class="p-3 bg-light rounded-3">
                        {!! $toko->deskripsi ?? '<span class="text-muted">Tidak ada deskripsi</span>' !!}
                    </div>
                </div>
            </div>
            @include('admin.toko.partials.produk-table', ['produks' => $produks])
        </div>
    </div>

@endsection


@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        // ================= TOGGLE =================
        function toggleTelepon(url, currentStatus) {

            let text = currentStatus
                ? 'Nonaktifkan nomor telepon toko ini? Pengguna tidak bisa dihubungi.'
                : 'Aktifkan nomor telepon toko ini agar bisa dihubungi pengguna?';

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
                        text: 'Sedang memperbarui status telepon toko',
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
                            Swal.fire(
                                'Gagal',
                                'Terjadi kesalahan saat mengubah status telepon',
                                'error'
                            );
                        });

                }
            });
        }

        // ================= DELETE =================
        function handleDelete(url) {

            Swal.fire({
                title: 'Hapus toko?',
                text: 'Toko akan dihapus permanen dan tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus data toko dari sistem',
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

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('admin.toko.index') }}";
                            });

                        })
                        .catch(() => {
                            Swal.fire(
                                'Error',
                                'Gagal menghapus toko',
                                'error'
                            );
                        });

                }
            });
        }

    </script>

@endpush