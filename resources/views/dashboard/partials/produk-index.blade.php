<div class="container py-4">
    <div class="row g-4">

        {{-- KOLOM UTAMA (KANAN) --}}
        <div class="col-lg-9 order-lg-1 order-1">
            @include('dashboard.partials.partials-toko-produk.card-toko')
            @include('dashboard.partials.partials-toko-produk.daftar-produk')
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-3 order-lg-2 order-2">
            @include('dashboard.partials.partials-toko-produk.sidebar-info')
        </div>
    </div>
</div>

@include('dashboard.partials.partials-toko-produk.modal-edit-toko')
@include('dashboard.partials.partials-toko-produk.modal-create-toko')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let userHasToko = false;
    let currentToko = null;

    // ================= SWEETALERT HELPER =================
    const swalLoading = (title = 'Memproses...') => {
        Swal.fire({
            title: title,
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            width: 400,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    };

    const swalSuccess = (text = 'Berhasil diproses') => {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: text,
            width: 400,
            timer: 1800,
            showConfirmButton: false
        });
    };

    const swalError = (text = 'Terjadi kesalahan') => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: text,
            width: 400
        });
    };

    const swalWarning = (title, text) => {
        return Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
            width: 400,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal'
        });
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

                    // LOGO
                    if (currentToko.logo && currentToko.logo.trim() !== "") {
                        logoImg.src = currentToko.logo;
                        logoImg.classList.remove('d-none');
                        logoIcon.classList.add('d-none');
                    } else {
                        logoImg.classList.add('d-none');
                        logoIcon.classList.remove('d-none');
                    }

                    // TEXT
                    document.getElementById('toko-nama').innerText = currentToko.nama_toko;
                    document.getElementById('toko-deskripsi').innerText = currentToko.deskripsi || 'Tidak ada deskripsi.';
                    document.getElementById('toko-telepon').innerText = currentToko.telepon || '-';

                    // BADGE
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

                    document.getElementById('toko-nama').innerText = 'Belum ada toko';
                    document.getElementById('toko-deskripsi').innerText = '-';
                    document.getElementById('toko-telepon').innerText = '-';

                    document.getElementById('toko-logo').classList.add('d-none');
                    document.getElementById('toko-logo-icon').classList.remove('d-none');

                    document.getElementById('btn-buat-toko').classList.remove('d-none');
                    document.getElementById('btn-edit-toko').classList.add('d-none');
                }
            })
            .catch(err => {
                console.error(err);
                swalError('Gagal memuat data toko');
            });
    }

    document.addEventListener('DOMContentLoaded', loadToko);

    // ================= BUAT TOKO =================
    document.getElementById('btn-buat-toko').addEventListener('click', () => {
        new bootstrap.Modal(document.getElementById('modal-tambah-toko')).show();
    });

    document.getElementById('form-tambah-toko').addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        if (!formData.has('telepon_aktif')) formData.append('telepon_aktif', 0);

        swalLoading('Menyimpan Data');

        fetch("{{ route('toko.store') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    swalSuccess(res.message);
                    bootstrap.Modal.getInstance(document.getElementById('modal-tambah-toko')).hide();
                    this.reset();
                    loadToko();
                } else {
                    swalError(res.message);
                }
            })
            .catch(() => swalError('Terjadi kesalahan saat menyimpan data'));
    });

    // ================= EDIT TOKO =================
    document.getElementById('btn-edit-toko').addEventListener('click', () => {
        if (!currentToko) return;

        document.getElementById('edit-nama').value = currentToko.nama_toko;
        document.getElementById('edit-deskripsi').value = currentToko.deskripsi;
        document.getElementById('edit-telepon').value = currentToko.telepon;
        document.getElementById('edit-telp-aktif').checked = parseInt(currentToko.telepon_aktif) === 1;

        new bootstrap.Modal(document.getElementById('modal-edit-toko')).show();
    });

    document.getElementById('form-edit-toko').addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        if (!formData.has('telepon_aktif')) formData.append('telepon_aktif', 0);

        swalLoading('Memperbarui Data');

        fetch("{{ url('dashboard/toko') }}/" + currentToko.id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    swalSuccess(res.message);
                    bootstrap.Modal.getInstance(document.getElementById('modal-edit-toko')).hide();
                    loadToko();
                } else {
                    swalError(res.message);
                }
            })
            .catch(() => swalError('Terjadi kesalahan saat update data'));
    });

    // ================= DELETE =================
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function () {

            const form = this.closest('form');
            const action = form.getAttribute('action');

            swalWarning('Hapus Produk?', 'Data tidak bisa dikembalikan!')
                .then(result => {
                    if (result.isConfirmed) {

                        // tampilkan loading (INI YANG KEMARIN HILANG)
                        swalLoading('Menghapus Data');

                        fetch(action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new URLSearchParams({
                                _method: 'DELETE'
                            })
                        })
                            .then(res => res.json())
                            .then(res => {
                                if (res.status === 'success') {
                                    swalSuccess(res.message);

                                    //HAPUS ROW TANPA RELOAD (LEBIH HALUS)
                                    const row = form.closest('tr');
                                    if (row) row.remove();

                                } else {
                                    swalError(res.message);
                                }
                            })
                            .catch(() => {
                                swalError('Gagal menghapus data');
                            });

                    }
                });

        });
    });

    // ================= VALIDASI =================
    document.getElementById('btn-tambah-produk').addEventListener('click', function (e) {
        if (!userHasToko) {
            e.preventDefault();
            swalError('Silakan buat toko terlebih dahulu sebelum menambah produk');
        }
    });
</script>