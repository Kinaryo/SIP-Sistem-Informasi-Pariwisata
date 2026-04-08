@extends('admin.layouts.app-admin')

@section('title', 'Data Wisata')
@section('page-title', 'Manajemen Wisata')

@section('content')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}"
            });
        </script>
    @endif

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

        /* FIX AKSI BIAR FULL */
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
                        <h5 class="mb-0 fw-bold">Daftar Wisata</h5>
                        <small class="text-muted">Filter & pencarian realtime</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <!-- SEARCH -->
                        <div class="input-group" style="width:220px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari wisata...">
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

                        <!-- BUTTON -->
                        <a href="{{ route('admin.tourism-places.create') }}" class="btn btn-primary"
                            data-bs-toggle="tooltip" title="Tambah wisata">
                            <i class="bi bi-plus-lg"></i>
                        </a>

                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableWisata">

                        <!-- HEADER -->
                        <thead class="table-light text-center align-middle">

                            <!-- ROW 1 (MERGE STATUS) -->
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama</th>
                                <th rowspan="2">Kategori</th>
                                <th rowspan="2">Lokasi</th>
                                <th rowspan="2">Author</th>
                                <th colspan="2">Status</th>
                                <th rowspan="2" width="260">Aksi</th>
                            </tr>

                            <!-- ROW 2 (DETAIL STATUS) -->
                            <tr>
                                <th>Keaktifan</th>
                                <th>Verifikasi</th>
                            </tr>

                        </thead>

                        <!-- BODY -->
                        <tbody>
                            @forelse ($places as $place)
                                <tr data-active="{{ $place->is_active }}" data-verified="{{ $place->is_verified }}">

                                    <td class="text-center">
                                        {{ $loop->iteration + $places->firstItem() - 1 }}
                                    </td>

                                    <td class="nama">
                                        <strong>{{ $place->name }}</strong><br>
                                        <small class="text-muted">{{ $place->slug }}</small>
                                    </td>

                                    <td class="text-center">{{ $place->category->name ?? '-' }}</td>
                                    <td class="text-center">{{ $place->location->city ?? '-' }}</td>
                                    <td class="text-center">{{ $place->author->name ?? '-' }}</td>

                                    <!-- STATUS KEAKTIFAN -->
                                    <td class="text-center">
                                        <span
                                            class="badge px-3 py-2 w-100 bg-{{ $place->is_active ? 'primary' : 'secondary' }}">
                                            {{ $place->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <!-- STATUS VERIFIKASI -->
                                    <td class="text-center">
                                        <span
                                            class="badge px-3 py-2 w-100 bg-{{ $place->is_verified ? 'success' : 'warning text-dark' }}">
                                            {{ $place->is_verified ? 'Verified' : 'Pending' }}
                                        </span>
                                    </td>

                                    <!-- AKSI -->
                                    <td class="text-center td-aksi">
                                        <div class="d-flex flex-wrap gap-1 justify-content-center">
                                            <!-- VIEW -->
                                            <a href="{{ route('admin.tourism-places.show', $place->slug) }}"
                                                class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('admin.tourism-places.edit', $place->slug) }}"
                                                class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- VERIFY -->
                                            @if ($place->is_verified)
                                                <button class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Batalkan Verifikasi"
                                                    onclick="handleAction('{{ route('admin.tourism-places.deactivate', $place->id) }}','PUT','Batalkan verifikasi?')">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Verifikasi"
                                                    onclick="handleAction('{{ route('admin.tourism-places.verify', $place->id) }}','PUT','Verifikasi wisata ini?')">
                                                    <i class="bi bi-check-circle"></i>
                                                </button>
                                            @endif

                                            <!-- ACTIVE -->
                                            @if ($place->is_active)
                                                <button class="btn btn-sm btn-secondary" data-bs-toggle="tooltip"
                                                    title="Nonaktifkan"
                                                    onclick="handleAction('{{ route('admin.tourism-places.deactivate', $place->id) }}','PUT','Nonaktifkan wisata ini?')">
                                                    <i class="bi bi-toggle-off"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-success" data-bs-toggle="tooltip" title="Aktifkan"
                                                    onclick="handleAction('{{ route('admin.tourism-places.activate', $place->id) }}','PUT','Aktifkan wisata ini?')">
                                                    <i class="bi bi-toggle-on"></i>
                                                </button>
                                            @endif

                                            <!-- DELETE -->
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Hapus"
                                                onclick="handleAction('{{ route('admin.tourism-places.destroy', $place->id) }}','DELETE','Hapus wisata ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Tidak ada data wisata
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $places->links() }}
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- PENTING: Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        // TOOLTIP FIX
        document.addEventListener("DOMContentLoaded", function () {
            const tooltipList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipList.map(el => new bootstrap.Tooltip(el));
        });

        // FILTER
        document.querySelectorAll('#searchInput, #filterActive, #filterVerified')
            .forEach(el => el.addEventListener('input', filterTable));

        function filterTable() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let active = document.getElementById('filterActive').value;
            let verified = document.getElementById('filterVerified').value;

            document.querySelectorAll('#tableWisata tbody tr').forEach(row => {

                let nama = row.querySelector('.nama').innerText.toLowerCase();
                let isActive = row.dataset.active;
                let isVerified = row.dataset.verified;

                let matchSearch = nama.includes(search);
                let matchActive = active === '' || active === isActive;
                let matchVerified = verified === '' || verified === isVerified;

                row.style.display = (matchSearch && matchActive && matchVerified) ? '' : 'none';
            });
        }

        // ACTION
        function handleAction(url, method, confirmText) {

            Swal.fire({
                title: 'Konfirmasi',
                text: confirmText,
                icon: 'warning',
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
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    })
                        .then(async res => {
                            let data = await res.json();

                            if (res.ok) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message || 'Berhasil',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                throw new Error(data.message || 'Gagal');
                            }
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: err.message
                            });
                        });
                }
            });
        }
    </script>

@endpush