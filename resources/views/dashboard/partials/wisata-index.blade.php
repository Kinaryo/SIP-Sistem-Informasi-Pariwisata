<style>
    .table {
        font-size: 0.8rem;
    }

    .table th,
    .table td {
        padding: 6px 8px;
        vertical-align: middle;
    }

    /* FIX HOVER FULL ROW */
    .table-hover tbody tr:hover td {
        background-color: #f1f5ff !important;
    }

    .badge {
        font-size: 0.7rem;
        padding: 6px;
    }

    .btn {
        font-size: 0.75rem;
        padding: 4px 8px;
    }

    .form-control,
    .form-select {
        font-size: 0.8rem;
        padding: 4px 8px;
    }

    .input-group-text {
        font-size: 0.8rem;
        padding: 4px 8px;
    }

    .pagination {
        gap: 4px;
    }

    .page-item .page-link {
        border-radius: 6px;
        border: none;
        color: #0d6efd;
        font-weight: 500;
        padding: 6px 10px;
        font-size: 0.8rem;
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

    td.aksi {
        white-space: nowrap;
    }

    /* FIX FLEX DI DALAM TD */
    td.aksi .action-wrapper {
        display: flex;
        gap: 4px;
        justify-content: center;
    }
</style>

<div class="row g-3 p-4">

    <!-- LEFT -->
    <div class="col-lg-9">

        <div class="card border-0 shadow-sm rounded-4 p-3">

            <!-- HEADER -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">

                <div>
                    <h5 class="mb-0 fw-bold">Daftar Tempat Wisata</h5>
                    <small class="text-muted">Kelola wisata, filter & pencarian realtime</small>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2">

                    <div class="input-group" style="width:200px;">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                    </div>

                    <select id="filterActive" class="form-select" style="width:140px;">
                        <option value="">Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>

                    <select id="filterVerified" class="form-select" style="width:150px;">
                        <option value="">Verifikasi</option>
                        <option value="1">Verified</option>
                        <option value="0">Pending</option>
                    </select>
                </div>
            </div>
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-circle"></i>
                <small>
                    Hanya destinasi wisata yang <strong>aktif</strong> dan <strong>telah diverifikasi</strong>
                    yang akan tampil ke publik.
                </small>
            </div>
            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="tableWisata">

                    <thead class="table-light text-center align-middle">
                        <tr>

                            <th rowspan="2">No</th>
                            <th rowspan="2">Wisata</th>
                            <th rowspan="2">Lokasi</th>

                            <th rowspan="2"> Tanggal</th>
                            <th colspan="2">Status</th>
                            <th rowspan="2" width="150">Aksi</th>
                        </tr>
                        <tr>
                            <th>Aktif</th>
                            <th>Verifikasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tourismPlaces as $place)
                            <tr data-active="{{ $place->is_active }}" data-verified="{{ $place->is_verified }}">

                                <td class="text-center">
                                    {{ $loop->iteration + $tourismPlaces->firstItem() - 1 }}
                                </td>

                                <td class="nama">
                                    <strong>{{ $place->name }}</strong><br>
                                    <small class="text-muted">{{ $place->slug }}</small>
                                </td>

                                <td class="text-center">
                                    {{ $place->location->city ?? '-' }}
                                </td>
                                <td class="text-center">
                                    {{ $place->created_at->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $place->is_active ? 'success' : 'secondary' }} px-2"
                                        style="width: 75px;">
                                        {{ $place->is_active ? 'Aktif' : 'NonAktif' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if($place->is_verified)
                                        <span class="badge bg-primary px-2" style="width: 75px;">Verified</span>
                                    @else
                                        <span class="badge bg-warning text-dark px-2" style="width: 75px;">Pending</span>
                                    @endif
                                </td>



                                <!-- FIX AKSI -->
                                <td class="text-center aksi">
                                    <div class="action-wrapper">

                                        <a href="{{ route('dashboard.showTourismPlaces', $place->slug) }}"
                                            class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('dashboard.editTourismPlaces', $place->slug) }}"
                                            class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <button class="btn btn-sm {{ $place->is_active ? 'btn-success' : 'btn-secondary' }}"
                                            data-bs-toggle="tooltip"
                                            title="{{ $place->is_active ? 'Nonaktifkan postingan' : 'Aktifkan postingan' }}"
                                            onclick="confirmToggle(
                                            '{{ route('dashboard.tourism.toggleActive', $place->id) }}',
                                            {{ $place->is_active ? 'true' : 'false' }},
                                            this
                                        )">

                                            <i class="bi {{ $place->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Hapus"
                                            onclick="handleDelete('{{ route('dashboard.tourism.destroy', $place->id) }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $tourismPlaces->links() }}
            </div>

        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">

            <h5 class="fw-bold mb-3 text-center">Alur Tambah Wisata</h5>

            <ol class="list-group list-group-numbered small">
                <li class="list-group-item">Login akun</li>
                <li class="list-group-item">Klik tambah</li>
                <li class="list-group-item">Isi data</li>
                <li class="list-group-item">Tunggu verifikasi</li>
                <li class="list-group-item">Muncul di daftar</li>
            </ol>

            <div class="mt-3 text-center">
                <a href="{{ route('dashboard.createTourismPlaces') }}" class="btn btn-primary w-100">
                    + Tambah Wisata
                </a>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // FIX TOOLTIP INIT
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    function confirmToggle(url, currentStatus, btnElement) {

        let actionText = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
        let buttonText = currentStatus ? 'Ya, nonaktifkan' : 'Ya, aktifkan';

        Swal.fire({
            title: 'Konfirmasi',
            text: `Yakin ingin ${actionText} postingan ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: buttonText,
            cancelButtonText: 'Batal'
        }).then(result => {

            if (result.isConfirmed) {

                // SET LOADING BUTTON
                let originalHTML = btnElement.innerHTML;
                btnElement.disabled = true;
                btnElement.innerHTML = `
                <span class="spinner-border spinner-border-sm"></span>
            `;

                fetch(url, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                    .then(res => res.json())
                    .then(res => {
                        Swal.fire('Berhasil', res.message, 'success')
                            .then(() => location.reload());
                    })
                    .catch(() => {
                        Swal.fire('Error', 'Gagal update', 'error');

                        // BALIKIN BUTTON KALAU ERROR
                        btnElement.disabled = false;
                        btnElement.innerHTML = originalHTML;
                    });
            }
        });
    }

    function handleDelete(url) {
        Swal.fire({
            title: 'Hapus wisata?',
            text: 'Data tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
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

            let match = nama.includes(search) &&
                (active === '' || active === isActive) &&
                (verified === '' || verified === isVerified);

            row.style.display = match ? '' : 'none';
        });
    }
</script>