<style>
    .table {
        font-size: 0.8rem;
    }

    .table th,
    .table td {
        padding: 6px 8px;
        vertical-align: middle;
    }

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

    td.aksi {
        white-space: nowrap;
    }

    td.aksi .action-wrapper {
        display: flex;
        gap: 4px;
        justify-content: center;
    }
</style>

<div class="row g-3">

    <!-- LEFT -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm rounded-4 p-3">

            <!-- HEADER -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">

                <div>
                    <h5 class="mb-0 fw-bold">Daftar Artikel</h5>
                    <small class="text-muted">Kelola artikel yang telah Anda buat</small>
                </div>

                <div class="d-flex flex-wrap gap-2">

                    <div class="input-group" style="width:200px;">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                    </div>

                    <select id="filterActive" class="form-select" style="width:140px;">
                        <option value="">Semua Status</option>
                        <option value="1">Publish/Aktif</option>
                        <option value="0">Draft/Nonaktif</option>
                    </select>

                    <select id="filterVerified" class="form-select" style="width:150px;">
                        <option value="">Verifikasi</option>
                        <option value="1">Verified</option>
                        <option value="0">Pending/Menunggu Verfikasi Admin</option>
                    </select>

                </div>
            </div>
            <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-circle"></i>
                <small>
                    Hanya artikel yang <strong>aktif</strong> dan <strong>telah diverifikasi</strong>
                    yang akan tampil ke publik.
                </small>
            </div>

            @if($artikels->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableArtikel">

                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Judul</th>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="2">Status</th>
                                <th rowspan="2" width="180">Aksi</th>
                            </tr>
                            <tr>
                                <th>Publish</th>
                                <th>Verifikasi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($artikels as $index => $a)
                                <tr data-active="{{ $a->is_active }}" data-verified="{{ $a->is_verified }}">

                                    <td class="text-center">{{ $index + 1 }}</td>

                                    <td class="judul fw-semibold">
                                        {{ \Illuminate\Support\Str::limit($a->judul, 40) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $a->created_at->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $a->is_active ? 'success' : 'secondary' }}  px-2"
                                            style="width: 75px;">
                                            {{ $a->is_active ? 'Publish' : 'Draft' }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if($a->is_verified)
                                            <span class="badge bg-primary px-2" style="width: 75px;">Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2" style="width: 75px;">Pending</span>
                                        @endif
                                    </td>



                                    <td class="text-center aksi">
                                        <div class="action-wrapper">

                                            <!-- LIHAT -->
                                            <a href="{{ route('artikel.showByOwner', $a->slug) }}" class="btn btn-info btn-sm"
                                                data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- EDIT -->
                                            <a href="{{ route('artikel.edit', $a->id) }}" class="btn btn-warning btn-sm"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <!-- TOGGLE AKTIF -->
                                            <button class="btn btn-sm {{ $a->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $a->is_active ? 'Jadikan Draft' : 'Publish artikel' }}" onclick="confirmToggle(
                                                                                    '{{ route('artikel.toggleActive', $a->id) }}',
                                                                                    {{ $a->is_active ? 'true' : 'false' }},
                                                                                    this
                                                                                )">
                                                <i class="bi {{ $a->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>

                                            <!-- DELETE -->
                                            <form action="{{ route('artikel.destroy', $a->id) }}" method="POST"
                                                class="form-delete d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm btn-delete"
                                                    data-judul="{{ $a->judul }}" data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <p class="text-center text-muted">Belum ada artikel</p>
            @endif

        </div>
    </div>

    <!-- RIGHT -->
    <div class="col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 h-100">

            <h5 class="fw-bold mb-3 text-center">Kelola Artikel</h5>

            <ol class="list-group list-group-numbered small">
                <li class="list-group-item">Klik tombol tulis artikel</li>
                <li class="list-group-item">Isi judul & konten</li>
                <li class="list-group-item">Tambahkan gambar</li>
                <li class="list-group-item">Klik simpan</li>
                <li class="list-group-item">Artikel langsung tampil</li>
            </ol>

            <div class="mt-3 text-center">
                <a href="{{ route('artikel.create') }}" class="btn btn-primary w-100">
                    <i class="bi bi-plus-circle me-1"></i>
                    Tulis Artikel
                </a>
            </div>

        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // TOOLTIP
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el);
    });

    // TOGGLE AKTIF
    function confirmToggle(url, currentStatus, btnElement) {

        let actionText = currentStatus ? 'menjadikan draft' : 'mempublish';
        let buttonText = currentStatus ? 'Ya, jadikan draft' : 'Ya, publish';

        Swal.fire({
            title: 'Konfirmasi',
            text: `Yakin ingin ${actionText} artikel ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: buttonText,
            cancelButtonText: 'Batal'
        }).then(result => {

            if (result.isConfirmed) {

                let originalHTML = btnElement.innerHTML;
                btnElement.disabled = true;
                btnElement.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;

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
                        btnElement.disabled = false;
                        btnElement.innerHTML = originalHTML;
                    });
            }
        });
    }

    // DELETE
    document.querySelectorAll('.form-delete').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            let btn = this.querySelector('.btn-delete');
            let judul = btn.dataset.judul;

            Swal.fire({
                title: 'Yakin hapus?',
                text: "Artikel \"" + judul + "\" akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {
                    btn.disabled = true;
                    btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span>`;
                    setTimeout(() => form.submit(), 500);
                }
            });
        });
    });

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

            let match = judul.includes(search) &&
                (active === '' || active === isActive) &&
                (verified === '' || verified === isVerified);

            row.style.display = match ? '' : 'none';
        });
    }
</script>