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

<div class="row g-4 p-4">

    {{-- KOLOM UTAMA --}}
    <div class="col-lg-9 order-lg-1 order-1">

        {{-- CARD TOKO --}}
        @include('dashboard.partials.partials-toko-produk.card-toko')



        {{-- TABEL PRODUK --}}
        <div class="card border-0 shadow-sm rounded-4 p-3 mt-2">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
                <div>
                    <h5 class="fw-bold mb-0">Daftar Produk</h5>
                    <small class="text-muted">Kelola produk yang Anda tawarkan</small>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    {{-- SEARCH --}}
                    <div class="input-group" style="width:200px;">
                        <span class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari produk...">
                    </div>

                    {{-- FILTER STATUS --}}
                    <select id="filterActive" class="form-select" style="width:130px;">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>

                    {{-- FILTER VERIFIKASI --}}
                    <select id="filterVerified" class="form-select" style="width:130px;">
                        <option value="">Verifikasi</option>
                        <option value="1">Verified</option>
                        <option value="0">Pending/Menunggu Verifikasi Admin</option>
                    </select>

                </div>
            </div>
            {{-- ALERT INFO --}}
            <div class="alert alert-warning d-flex align-items-center gap-2 mt-3">
                <i class="bi bi-exclamation-circle"></i>
                <small>
                    Hanya produk yang <strong>aktif</strong> dan <strong>telah diverifikasi</strong>
                    yang akan tampil ke publik.
                </small>
            </div>
            @if($produks->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="tableProduk">
                        <thead class="table-light text-center">
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Nama Produk</th>
                                <th rowspan="2">Harga</th>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="2">Status</th>
                                <th rowspan="2" width="180">Aksi</th>
                            </tr>
                            <tr>
                                <th>Aktif</th>
                                <th>Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produks as $i => $produk)
                                <tr data-active="{{ $produk->is_active }}" data-verified="{{ $produk->is_verified }}">
                                    <td class="text-center">{{ $i + 1 }}</td>
                                    <td class="nama-produk fw-semibold">
                                        {{ \Illuminate\Support\Str::limit($produk->nama_produk, 40) }}
                                    </td>

                                    <td class="text-center">
                                        Rp {{ number_format($produk->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $produk->created_at->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $produk->is_active ? 'success' : 'secondary' }} px-2" style="width: 75px;">
                                            {{ $produk->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($produk->is_verified)
                                            <span class="badge bg-primary px-2" style="width: 75px;">Verified</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2" style="width: 75px;">Pending</span>
                                        @endif
                                    </td>
                                    <td class="text-center aksi">
                                        <div class="action-wrapper">
                                            <a href="{{ route('produk.show', $produk->id) }}" class="btn btn-info btn-sm"
                                                data-bs-toggle="tooltip" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-warning btn-sm"
                                                data-bs-toggle="tooltip" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <button
                                                class="btn btn-sm {{ $produk->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                data-bs-toggle="tooltip"
                                                title="{{ $produk->is_active ? 'Nonaktifkan produk' : 'Aktifkan produk' }}"
                                                onclick="confirmToggleProduk('{{ route('produk.toggleActive', $produk->id) }}', {{ $produk->is_active ? 'true' : 'false' }}, this)">
                                                <i class="bi {{ $produk->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>

                                            <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm btn-delete-produk"
                                                    title="Hapus">
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
                <p class="text-center text-muted">Belum ada produk</p>
            @endif
        </div>
    </div>

    {{-- SIDEBAR --}}
    <div class="col-lg-3 order-lg-2 order-2">
        @include('dashboard.partials.partials-toko-produk.sidebar-info')
    </div>
</div>

@include('dashboard.partials.partials-toko-produk.modal-edit-toko')
@include('dashboard.partials.partials-toko-produk.modal-create-toko')

{{-- SCRIPTS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // GLOBAL VARIABLES
    let userHasToko = false;
    let currentToko = null;

    // ================= FILTER & SEARCH LOGIC =================
    document.querySelectorAll('#searchInput, #filterActive, #filterVerified')
        .forEach(el => el.addEventListener('input', filterTable));

    function filterTable() {
        let search = document.getElementById('searchInput').value.toLowerCase();
        let active = document.getElementById('filterActive').value;
        let verified = document.getElementById('filterVerified').value;

        document.querySelectorAll('#tableProduk tbody tr').forEach(row => {
            let namaProduk = row.querySelector('.nama-produk').innerText.toLowerCase();
            let isActive = row.dataset.active;
            let isVerified = row.dataset.verified;

            let match = namaProduk.includes(search) &&
                (active === '' || active === isActive) &&
                (verified === '' || verified === isVerified);

            row.style.display = match ? '' : 'none';
        });
    }

    // ================= SWEETALERT HELPERS =================
    const swalLoading = (title = 'Memproses...') => {
        Swal.fire({
            title: title,
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });
    };

    const swalSuccess = (text = 'Berhasil diproses') => {
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: text, timer: 1800, showConfirmButton: false });
    };

    const swalError = (text = 'Terjadi kesalahan') => {
        Swal.fire({ icon: 'error', title: 'Gagal!', text: text });
    };

    // ================= LOAD DATA TOKO =================
    function loadToko() {
        fetch("{{ route('toko.index') }}", {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'has_toko') {
                    userHasToko = true;
                    currentToko = res.toko;

                    const logoImg = document.getElementById('toko-logo');
                    const logoIcon = document.getElementById('toko-logo-icon');

                    if (currentToko.logo) {
                        logoImg.src = currentToko.logo;
                        logoImg.classList.remove('d-none');
                        logoIcon.classList.add('d-none');
                    } else {
                        logoImg.classList.add('d-none');
                        logoIcon.classList.remove('d-none');
                    }

                    document.getElementById('toko-nama').innerText = currentToko.nama_toko;
                    document.getElementById('toko-deskripsi').innerText = currentToko.deskripsi || 'Tidak ada deskripsi.';
                    document.getElementById('toko-telepon').innerText = currentToko.telepon || '-';

                    const badge = document.getElementById('badge-telepon');
                    if (parseInt(currentToko.telepon_aktif) === 1) {
                        badge.innerText = 'Aktif';
                        badge.className = 'badge bg-success-subtle text-success border border-success-subtle px-2 ms-1';
                    } else {
                        badge.innerText = 'Non-aktif';
                        badge.className = 'badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 ms-1';
                    }

                    document.getElementById('btn-edit-toko').classList.remove('d-none');
                    document.getElementById('btn-buat-toko').classList.add('d-none');
                } else {
                    userHasToko = false;
                    document.getElementById('btn-buat-toko').classList.remove('d-none');
                    document.getElementById('btn-edit-toko').classList.add('d-none');
                }
            })
            .catch(err => console.error('Gagal memuat toko:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadToko();
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    });

    // ================= ACTION TOKO (CREATE & EDIT) =================
    document.getElementById('btn-buat-toko')?.addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('modal-tambah-toko')).show();
    });

    document.getElementById('btn-edit-toko')?.addEventListener('click', () => {
        if (!currentToko) return;
        document.getElementById('edit-nama').value = currentToko.nama_toko;
        document.getElementById('edit-deskripsi').value = currentToko.deskripsi;
        document.getElementById('edit-telepon').value = currentToko.telepon;
        document.getElementById('edit-telp-aktif').checked = parseInt(currentToko.telepon_aktif) === 1;
        new bootstrap.Modal(document.getElementById('modal-edit-toko')).show();
    });

    ['form-tambah-toko', 'form-edit-toko'].forEach(id => {
        document.getElementById(id)?.addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = id === 'form-tambah-toko' ? "{{ route('toko.store') }}" : "{{ url('dashboard/toko') }}/" + currentToko.id;

            swalLoading('Menyimpan Data...');
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        swalSuccess(res.message);
                        bootstrap.Modal.getInstance(this.closest('.modal')).hide();
                        loadToko();
                    } else { swalError(res.message); }
                });
        });
    });

    // ================= ACTION PRODUK =================
    function confirmToggleProduk(url, currentStatus, btnElement) {
        let actionText = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
        Swal.fire({
            title: 'Konfirmasi',
            text: `Yakin ingin ${actionText} produk ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                btnElement.disabled = true;
                fetch(url, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                    .then(res => res.json())
                    .then(res => {
                        swalSuccess(res.message);
                        setTimeout(() => location.reload(), 1000);
                    })
                    .catch(() => swalError('Gagal update status'));
            }
        });
    }

    document.querySelectorAll('.btn-delete-produk').forEach(btn => {
        btn.addEventListener('click', function () {
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus Produk?',
                text: 'Data tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    swalLoading('Menghapus...');
                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: new URLSearchParams({ _method: 'DELETE' })
                    })
                        .then(res => res.json())
                        .then(res => {
                            swalSuccess(res.message);
                            form.closest('tr').remove();
                        })
                        .catch(() => swalError('Gagal menghapus'));
                }
            });
        });
    });

    document.getElementById('btn-tambah-produk').addEventListener('click', function (e) {
        if (!userHasToko) {
            e.preventDefault();
            Swal.fire('Error', 'Silakan buat toko terlebih dahulu sebelum menambah produk', 'error');
        }
    });
</script>