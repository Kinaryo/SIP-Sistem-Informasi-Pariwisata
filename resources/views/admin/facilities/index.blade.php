@extends('admin.layouts.app-admin')

@section('title', 'Kelola Fasilitas')
@section('page-title', 'Kelola Fasilitas')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">Daftar Fasilitas Yang Bisa Ditambahkan</h4>
            <button class="btn btn-primary" onclick="openModal()">Tambah Fasilitas</button>
        </div>

        <table class="table table-striped align-middle border">
            <thead class="table-light">
                <tr>
                    <th class="border text-center" style="width:50px;">No</th>
                    <th class="border text-center">Nama</th>
                    <th class="border text-center" style="width:70px;">Gambar</th>
                    <th class="border text-center" style="width:200px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="facility-table">
                @foreach($facilities as $facility)
                    <tr id="facility-{{ $facility->id }}">
                        <td class="border text-center">{{ $loop->iteration }}</td>
                        <td class="border">{{ $facility->name }}</td>
                        <td class="text-center border">
                            @if($facility->image)
                                @php
                                    $imgSrc = Str::startsWith($facility->image, ['http://', 'https://'])
                                        ? $facility->image
                                        : asset('storage/' . $facility->image);
                                @endphp
                                <img src="{{ $imgSrc }}" alt="{{ $facility->name }}"
                                    style="width:50px; height:50px; object-fit:cover; border:1px solid #ddd; border-radius:4px;">
                            @else
                                <div style="width:50px; height:50px; display:flex; align-items:center; justify-content:center;
                                            border:1px solid #ddd; border-radius:4px; background:#f0f0f0;">
                                    <span style="color:green; font-size:20px;">✔</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-center border">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-info" onclick="editFacility({{ $facility->id }})">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteFacility({{ $facility->id }})">
                                    <i class="bi bi-trash-fill"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>

    <!-- Modal -->
    <div class="modal fade" id="facilityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="facilityForm" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Fasilitas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="facilityId">
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Gambar</label>
                            <input type="file" id="image" name="image" class="form-control">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let facilityModal = new bootstrap.Modal(document.getElementById('facilityModal'));

        /* ================= SWEET ALERT LOADING ================= */
        function showLoading(text = 'Memproses...') {
            Swal.fire({
                title: text,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        function closeLoading() {
            Swal.close();
        }

        /* ================= OPEN MODAL ================= */
        function openModal() {
            document.getElementById('facilityForm').reset();
            document.getElementById('facilityId').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Fasilitas';
            facilityModal.show();
        }

        /* ================= EDIT ================= */
        function editFacility(id) {
            showLoading('Mengambil data...');

            fetch(`/admin/facilities/${id}`)
                .then(res => res.json())
                .then(data => {
                    closeLoading();

                    document.getElementById('facilityId').value = data.id;
                    document.getElementById('name').value = data.name;
                    document.getElementById('modalTitle').innerText = 'Edit Fasilitas';
                    facilityModal.show();
                })
                .catch(() => {
                    closeLoading();
                    Swal.fire('Error', 'Gagal mengambil data', 'error');
                });
        }

        /* ================= SUBMIT (CREATE & UPDATE) ================= */
        document.getElementById('facilityForm').addEventListener('submit', function (e) {
            e.preventDefault();
            showLoading('Menyimpan data...');

            let id = document.getElementById('facilityId').value;
            let formData = new FormData(this);

            let url = id ? `/admin/facilities/${id}` : '/admin/facilities';
            if (id) formData.append('_method', 'PUT');

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(data => {
                    closeLoading();
                    facilityModal.hide();

                    Swal.fire('Berhasil', data.message, 'success')
                        .then(() => location.reload());
                })
                .catch(() => {
                    closeLoading();
                    Swal.fire('Error', 'Terjadi kesalahan', 'error');
                });
        });

        /* ================= DELETE ================= */
        function deleteFacility(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    showLoading('Menghapus data...');

                    fetch(`/admin/facilities/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: new URLSearchParams({ _method: 'DELETE' })
                    })
                        .then(res => res.json())
                        .then(data => {
                            closeLoading();
                            document.getElementById(`facility-${id}`).remove();
                            Swal.fire('Terhapus!', data.message, 'success');
                        })
                        .catch(() => {
                            closeLoading();
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        });
                }
            });
        }
    </script>
@endpush