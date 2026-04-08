@extends('admin.layouts.app-admin')

@section('title', 'Data Produk')
@section('page-title', 'Manajemen Produk')

@section('content')

    <style>
        .pagination {
            gap: 6px;
        }

        .page-item .page-link {
            border-radius: 8px;
            border: none;
            color: #0d6efd;
            font-weight: 500;
            padding: 8px 14px;
        }

        .page-item .page-link:hover {
            background-color: #0d6efd;
            color: #fff;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            color: #fff;
        }

        .page-item.disabled .page-link {
            color: #aaa;
            background: #f8f9fa;
        }

        /* FIX AKSI */
        td.aksi {
            white-space: nowrap;
        }

        td.aksi .btn {
            padding: 4px 8px;
        }
    </style>

    <div class="row g-4">
        <div class="col-md-12">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- HEADER -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

                    <div>
                        <h5 class="mb-0 fw-bold">Daftar Produk</h5>
                        <small class="text-muted">Kelola produk, filter & pencarian realtime</small>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <!-- SEARCH -->
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari produk...">
                        </div>

                        <!-- FILTER ACTIVE -->
                        <select id="filterActive" class="form-select" style="width:160px;">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        <!-- FILTER VERIFIED -->
                        <select id="filterVerified" class="form-select" style="width:170px;">
                            <option value="">Semua Verifikasi</option>
                            <option value="1">Verified</option>
                            <option value="0">Belum</option>
                        </select>

                        <!-- TAMBAH -->
                        <a href="{{ route('admin.produks.create') }}"
                            class="btn btn-primary d-flex align-items-center gap-1 px-3">
                            <i class="bi bi-plus-lg"></i>
                            <span class="d-none d-md-inline">Tambah Sebagai Admin</span>
                        </a>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableProduk">

                        <thead class="table-light text-center align-middle">

                            <!-- HEADER UTAMA -->
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Produk</th>
                                <th rowspan="2">Harga</th>
                                <th rowspan="2">Toko</th>
                                <th rowspan="2">Owner</th>
                                <th colspan="2">Status</th>
                                <th rowspan="2" width="200">Aksi</th>
                            </tr>

                            <!-- SUB HEADER -->
                            <tr>
                                <th>Keaktifan</th>
                                <th>Verifikasi</th>
                            </tr>

                        </thead>

                        <tbody>
                            @forelse ($produks as $produk)
                                <tr data-active="{{ $produk->is_active }}" data-verified="{{ $produk->is_verified }}">

                                    <td class="text-center">
                                        {{ $loop->iteration + $produks->firstItem() - 1 }}
                                    </td>

                                    <td class="nama">
                                        <strong>{{ $produk->nama_produk }}</strong><br>
                                        <small class="text-muted">
                                            {!! Str::limit(strip_tags($produk->deskripsi), 60) !!}
                                        </small>
                                    </td>

                                    <td class="text-center">
                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        {{ $produk->user->toko->nama_toko ?? '-' }}
                                    </td>

                                    <td class="text-center">
                                        {{ $produk->user->name ?? '-' }}
                                    </td>

                                    <!-- STATUS AKTIF -->
                                    <td class="text-center">
                                        <span class="badge w-100 py-2 bg-{{ $produk->is_active ? 'success' : 'secondary' }}">
                                            {{ $produk->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <!-- STATUS VERIFIKASI -->
                                    <td class="text-center">
                                        <span
                                            class="badge w-100 py-2 bg-{{ $produk->is_verified ? 'primary' : 'warning text-dark' }}">
                                            {{ $produk->is_verified ? 'Verified' : 'Belum' }}
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="text-center td-aksi">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <!-- VIEW -->
                                            <a href="{{ route('admin.produks.show', $produk->id) }}" class="btn btn-sm btn-info"
                                                data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('admin.produks.edit', $produk->id) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- TOGGLE ACTIVE -->
                                            <button
                                                class="btn btn-sm {{ $produk->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $produk->is_active ? 'Nonaktifkan Produk' : 'Aktifkan Produk' }}"
                                                onclick="confirmToggle(
                                            '{{ route('admin.produks.toggleActive', $produk->id) }}',
                                            {{ $produk->is_active ? 'true' : 'false' }},
                                            'active'
                                        )">
                                                <i class="bi {{ $produk->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>

                                            <!-- TOGGLE VERIFIED -->
                                            <button
                                                class="btn btn-sm {{ $produk->is_verified ? 'btn-primary' : 'btn-warning' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $produk->is_verified ? 'Batalkan Verifikasi' : 'Verifikasi Produk' }}"
                                                onclick="confirmToggle(
                                            '{{ route('admin.produks.toggleVerified', $produk->id) }}',
                                            {{ $produk->is_verified ? 'true' : 'false' }},
                                            'verified'
                                        )">
                                                <i
                                                    class="bi {{ $produk->is_verified ? 'bi-patch-check-fill' : 'bi-patch-exclamation' }}"></i>
                                            </button>

                                            <!-- DELETE -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus"
                                                onclick="handleDelete('{{ route('admin.produks.destroy', $produk->id) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>

                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada produk</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $produks->links() }}
                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <!-- WAJIB -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ICON -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        // ================= TOOLTIP FIX =================
        function initTooltip() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el)
            });
        }
        document.addEventListener("DOMContentLoaded", initTooltip);


        // ================= TOGGLE =================
        function confirmToggle(url, currentStatus, type) {

            let text = '';

            if (type === 'active') {
                text = currentStatus
                    ? 'Produk akan dinonaktifkan.'
                    : 'Produk akan diaktifkan.';
            } else {
                text = currentStatus
                    ? 'Batalkan verifikasi produk?'
                    : 'Verifikasi produk ini?';
            }

            Swal.fire({
                title: 'Konfirmasi',
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Memproses...',
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
                title: 'Hapus Produk?',
                text: 'Data tidak bisa dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {

                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Menghapus...',
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
                                .then(() => location.reload());
                        })
                        .catch(() => {
                            Swal.fire('Error', 'Gagal hapus', 'error');
                        });
                }
            });
        }


        // ================= FILTER =================
        document.querySelectorAll('#searchInput, #filterActive, #filterVerified')
            .forEach(el => el.addEventListener('input', filterTable));

        function filterTable() {

            let search = document.getElementById('searchInput').value.toLowerCase();
            let active = document.getElementById('filterActive').value;
            let verified = document.getElementById('filterVerified').value;

            document.querySelectorAll('#tableProduk tbody tr').forEach(row => {

                let nama = row.querySelector('.nama').innerText.toLowerCase();
                let isActive = row.dataset.active;
                let isVerified = row.dataset.verified;

                let matchSearch = nama.includes(search);
                let matchActive = active === '' || active === isActive;
                let matchVerified = verified === '' || verified === isVerified;

                row.style.display = (matchSearch && matchActive && matchVerified) ? '' : 'none';
            });
        }

    </script>

@endpush