@extends('admin.layouts.app-admin')

@section('title', 'Data Artikel')
@section('page-title', 'Manajemen Artikel')

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
        .td-aksi {
            white-space: nowrap;
        }

        .td-aksi .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="row g-4">
        <div class="col-md-12">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- HEADER -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">

                    <div>
                        <h5 class="mb-0 fw-bold">Daftar Artikel</h5>
                        <small class="text-muted">Filter & pencarian realtime</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <!-- SEARCH -->
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari judul...">
                        </div>

                        <!-- FILTER ACTIVE -->
                        <select id="filterActive" class="form-select" style="width:160px;">
                            <option value="">Semua</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        <!-- FILTER VERIFIED -->
                        <select id="filterVerified" class="form-select" style="width:170px;">
                            <option value="">Semua</option>
                            <option value="1">Verified</option>
                            <option value="0">Belum</option>
                        </select>

                        <!-- BUTTON -->
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary" data-bs-toggle="tooltip"
                            title="Tambah artikel">
                            <i class="bi bi-plus-lg"></i>
                        </a>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableArtikel">

                        <thead class="table-light text-center align-middle">

                            <!-- HEADER UTAMA -->
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Judul</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Author</th>
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
                            @forelse ($artikels as $artikel)
                                <tr data-active="{{ $artikel->is_active }}" data-verified="{{ $artikel->is_verified }}">

                                    <td class="text-center">
                                        {{ $loop->iteration + $artikels->firstItem() - 1 }}
                                    </td>

                                    <td class="judul">
                                        <strong>{{ $artikel->judul }}</strong><br>
                                        <small class="text-muted">{{ $artikel->slug }}</small>
                                    </td>

                                    <td class="text-center">
                                        {{ $artikel->created_at?->format('d M Y') }}
                                    </td>

                                    <td class="text-center">
                                        {{ $artikel->user->name ?? '-' }}
                                    </td>

                                    <!-- STATUS AKTIF -->
                                    <td class="text-center">
                                        <span class="badge w-100 py-2 bg-{{ $artikel->is_active ? 'success' : 'secondary' }}">
                                            {{ $artikel->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <!-- STATUS VERIFIKASI -->
                                    <td class="text-center">
                                        <span
                                            class="badge w-100 py-2 bg-{{ $artikel->is_verified ? 'primary' : 'warning text-dark' }}">
                                            {{ $artikel->is_verified ? 'Verified' : 'Belum' }}
                                        </span>
                                    </td>

                                    <!-- AKSI -->

                                    <td class="text-center td-aksi">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <!-- VIEW -->
                                            <a href="{{ route('admin.articles.show', $artikel->id) }}"
                                                class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('admin.articles.edit', $artikel->id) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <!-- TOGGLE ACTIVE -->
                                            <button
                                                class="btn btn-sm {{ $artikel->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                data-bs-toggle="tooltip" title="Toggle Aktif"
                                                onclick="confirmToggle('{{ route('admin.articles.toggleActive', $artikel->id) }}', {{ $artikel->is_active ? 'true' : 'false' }}, 'active')">
                                                <i class="bi {{ $artikel->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>

                                            <!-- TOGGLE VERIFIED -->
                                            <button
                                                class="btn btn-sm {{ $artikel->is_verified ? 'btn-primary' : 'btn-warning' }}"
                                                data-bs-toggle="tooltip" title="Toggle Verifikasi"
                                                onclick="confirmToggle('{{ route('admin.articles.toggleVerified', $artikel->id) }}', {{ $artikel->is_verified ? 'true' : 'false' }}, 'verified')">
                                                <i
                                                    class="bi {{ $artikel->is_verified ? 'bi-patch-check-fill' : 'bi-patch-exclamation' }}"></i>
                                            </button>

                                            <!-- DELETE -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus"
                                                onclick="handleDelete('{{ route('admin.articles.destroy', $artikel->id) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada artikel</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $artikels->links() }}
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- WAJIB BIAR TOOLTIP HIDUP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>

        // TOOLTIP
        document.addEventListener("DOMContentLoaded", function () {
            const tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipList.map(el => new bootstrap.Tooltip(el));
        });

        // TOGGLE
        function confirmToggle(url, currentStatus, type) {

            let text = type === 'active'
                ? (currentStatus ? 'Nonaktifkan artikel?' : 'Aktifkan artikel?')
                : (currentStatus ? 'Batalkan verifikasi?' : 'Verifikasi artikel?');

            Swal.fire({
                title: 'Konfirmasi',
                text: text,
                icon: 'question',
                showCancelButton: true
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
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                        .then(async res => {
                            let data = await res.json();

                            if (res.ok) {
                                Swal.fire('Berhasil', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', err.message, 'error');
                        });
                }
            });
        }

        // DELETE
        function handleDelete(url) {
            Swal.fire({
                title: 'Yakin?',
                text: 'Artikel akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true
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
                        .then(async res => {
                            let data = await res.json();

                            if (res.ok) {
                                Swal.fire('Berhasil', data.message, 'success')
                                    .then(() => location.reload());
                            } else {
                                throw new Error(data.message);
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', err.message, 'error');
                        });
                }
            });
        }

        // FILTER
        document.querySelectorAll('#searchInput, #filterActive, #filterVerified')
            .forEach(el => el.addEventListener('input', filterTable));

        function filterTable() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let active = document.getElementById('filterActive').value;
            let verified = document.getElementById('filterVerified').value;

            document.querySelectorAll('#tableArtikel tbody tr').forEach(row => {

                let judul = row.querySelector('.judul').innerText.toLowerCase();
                let isActive = row.dataset.active;
                let isVerified = row.dataset.verified;

                let matchSearch = judul.includes(search);
                let matchActive = active === '' || active === isActive;
                let matchVerified = verified === '' || verified === isVerified;

                row.style.display = (matchSearch && matchActive && matchVerified) ? '' : 'none';
            });
        }

    </script>

@endpush