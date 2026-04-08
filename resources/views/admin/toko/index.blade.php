@extends('admin.layouts.app-admin')

@section('title', 'Data Toko')
@section('page-title', 'Manajemen Toko')

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

        /* FIX AKSI BIAR RAPI */
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
                        <h5 class="mb-0 fw-bold">Daftar Toko</h5>
                        <small class="text-muted">Kelola data toko pengguna</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <!-- SEARCH -->
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari toko...">
                        </div>

                        <!-- FILTER -->
                        <select id="filterStatus" class="form-select" style="width:180px;">
                            <option value="">Semua Status</option>
                            <option value="1">Telepon Aktif</option>
                            <option value="0">Telepon Nonaktif</option>
                        </select>

                        <!-- TAMBAH -->
                        <a href="{{ route('admin.toko.create') }}"
                            class="btn btn-primary d-flex align-items-center gap-1 px-3">
                            <i class="bi bi-plus-lg"></i>
                            <span class="d-none d-md-inline">Tambah</span>
                        </a>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableToko">

                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width:60px;">No</th>
                                <th>Toko</th>
                                <th>No. Telepon</th>
                                <th>Owner</th>
                                <th>Jumlah Produk</th>
                                <th>Status Telepon</th>
                                <th style="width:220px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($tokos as $toko)
                                <tr data-status="{{ $toko->telepon_aktif }}">

                                    <!-- NO -->
                                    <td class="text-center">
                                        {{ $loop->iteration + $tokos->firstItem() - 1 }}
                                    </td>

                                    <!-- TOKO -->
                                    <td class="nama">
                                        <strong>{{ $toko->nama_toko }}</strong><br>
                                        <small class="text-muted">{{ $toko->slug }}</small>
                                    </td>

                                    
                                    <td class="text-center">
                                        {{ $toko->telepon ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $toko->user->name ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                            {{ $toko->produks_count ?? '-'}}
                                    </td>
                                    <!-- STATUS -->
                                    <td class="text-center">
                                        <span class="badge px-3 py-2 bg-{{ $toko->telepon_aktif ? 'success' : 'secondary' }}">
                                            {{ $toko->telepon_aktif ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="text-center">

                                        <div class="d-flex justify-content-center flex-wrap gap-1">

                                            <!-- VIEW -->
                                            <a href="{{ route('admin.toko.show', $toko->id) }}" class="btn btn-sm btn-info"
                                                data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('admin.toko.edit', $toko->id) }}" class="btn btn-sm btn-warning"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- TOGGLE -->
                                            <button
                                                class="btn btn-sm {{ $toko->telepon_aktif ? 'btn-success' : 'btn-secondary' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $toko->telepon_aktif ? 'Nonaktifkan Telepon' : 'Aktifkan Telepon' }}"
                                                onclick="confirmToggle(
                                                    '{{ route('admin.toko.toggleTelepon', $toko->id) }}',
                                                    {{ $toko->telepon_aktif ? 'true' : 'false' }}
                                                )">
                                                <i class="bi {{ $toko->telepon_aktif ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>

                                            <!-- DELETE -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus"
                                                onclick="handleDelete('{{ route('admin.toko.destroy', $toko->id) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data toko</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $tokos->links() }}
                </div>

            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <!-- WAJIB: BOOTSTRAP JS -->
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


        // ================= FILTER + SEARCH =================
        document.querySelectorAll('#searchInput, #filterStatus')
            .forEach(el => el.addEventListener('input', filterTable));

        function filterTable() {

            let search = document.getElementById('searchInput').value.toLowerCase();
            let status = document.getElementById('filterStatus').value;

            document.querySelectorAll('#tableToko tbody tr').forEach(row => {

                let nama = row.querySelector('.nama').innerText.toLowerCase();
                let rowStatus = row.dataset.status;

                let matchSearch = nama.includes(search);
                let matchStatus = status === '' || status === rowStatus;

                row.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        }


        // ================= TOGGLE =================
        function confirmToggle(url, currentStatus) {

            let text = currentStatus
                ? 'Nonaktifkan telepon toko ini?'
                : 'Aktifkan telepon toko ini?';

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
                                timer: 1200,
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
                title: 'Hapus toko?',
                text: 'Data toko akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
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
                            Swal.fire('Error', 'Gagal menghapus toko', 'error');
                        });

                }
            });
        }

    </script>

@endpush