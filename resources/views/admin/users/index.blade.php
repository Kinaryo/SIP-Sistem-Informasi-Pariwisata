@extends('admin.layouts.app-admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')

    <style>
        .pagination {
            gap: 6px;
        }

        .page-item .page-link {
            border-radius: 8px;
            border: none;
            font-weight: 500;
            padding: 8px 14px;
        }

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
                        <h5 class="mb-0 fw-bold">Daftar User</h5>
                        <small class="text-muted">Kelola user & status</small>
                    </div>

                    <div class="d-flex flex-wrap gap-2 align-items-center">

                        <div class="input-group" style="width:250px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari nama atau email...">
                        </div>

                        <select id="filterStatus" class="form-select" style="width:160px;">
                            <option value="">Semua Status</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>

                        <button class="btn btn-primary" id="addUserBtn">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>

                    </div>

                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center" id="tableUser">

                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th class="text-start">Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Alamat</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($users as $user)
                                <tr data-id="{{ $user->id }}" data-status="{{ $user->is_active }}">

                                    <td>{{ $loop->iteration }}</td>

                                    <td class="text-start nama">{{ $user->name }}</td>

                                    <td class="email">{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->address }}</td>

                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>

                                    <td class="aksi">
                                        <div class="d-flex justify-content-center gap-1">

                                            <button class="btn btn-sm btn-warning editBtn" data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}" data-address="{{ $user->address }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <button class="btn btn-sm btn-danger deleteBtn">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <button class="btn btn-sm toggleBtn 
                                                {{ $user->is_active ? 'btn-success' : 'btn-secondary' }}"
                                                data-status="{{ $user->is_active }}">
                                                <i class="bi {{ $user->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                                            </button>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="userModal">
        <div class="modal-dialog">
            <div class="modal-content p-3">

                <h5 id="modalTitle"></h5>

                <form id="userForm">
                    @csrf
                    <input type="hidden" id="userId">

                    <div class="mb-2">
                        <label>Nama</label>
                        <input type="text" id="name" class="form-control" placeholder="Masukkan nama">
                    </div>

                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" id="email" class="form-control" placeholder="Masukkan email">
                    </div>

                    <div class="mb-2">
                        <label>Role</label>
                        <select id="role" class="form-control">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label>Alamat</label>
                        <input type="text" id="address" class="form-control" placeholder="Masukkan alamat">
                    </div>

                    <div class="mb-2">
                        <label>Password</label>
                        <input type="password" id="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>

                    <div class="text-end mt-3">
                        <button class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const modal = new bootstrap.Modal(document.getElementById('userModal'));

            // ================= SEARCH + FILTER =================
            document.getElementById('searchInput').addEventListener('input', filterTable);
            document.getElementById('filterStatus').addEventListener('change', filterTable);

            function filterTable() {

                let search = document.getElementById('searchInput').value.toLowerCase();
                let status = document.getElementById('filterStatus').value;

                document.querySelectorAll('#tableUser tbody tr').forEach(row => {

                    let nama = row.querySelector('.nama').innerText.toLowerCase();
                    let email = row.querySelector('.email').innerText.toLowerCase();
                    let rowStatus = row.dataset.status;

                    let matchSearch = nama.includes(search) || email.includes(search);
                    let matchStatus = status === '' || status === rowStatus;

                    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
                });
            }

            // ================= ADD =================
            document.getElementById('addUserBtn').onclick = () => {
                document.getElementById('modalTitle').innerText = 'Tambah User';
                document.getElementById('userForm').reset();
                document.getElementById('userId').value = '';
                modal.show();
            };

            // ================= EDIT =================
            document.querySelectorAll('.editBtn').forEach(btn => {
                btn.addEventListener('click', () => {

                    document.getElementById('modalTitle').innerText = 'Edit User';

                    document.getElementById('userId').value = btn.dataset.id;
                    document.getElementById('name').value = btn.dataset.name;
                    document.getElementById('email').value = btn.dataset.email;
                    document.getElementById('role').value = btn.dataset.role;
                    document.getElementById('address').value = btn.dataset.address;

                    modal.show();
                });
            });

            // ================= SUBMIT (FIX UTAMA) =================
            document.getElementById('userForm').addEventListener('submit', function (e) {
                e.preventDefault();

                let btn = this.querySelector('button');
                btn.disabled = true;

                let id = document.getElementById('userId').value;

                let url = id ? `/admin/users/${id}` : `/admin/users`;
                let method = id ? 'PUT' : 'POST';

                let formData = {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    role: document.getElementById('role').value,
                    address: document.getElementById('address').value,
                    password: document.getElementById('password').value
                };

                // Laravel biasanya butuh _method untuk PUT
                if (method === 'PUT') {
                    formData._method = 'PUT';
                    method = 'POST';
                }

                // LOADING
                Swal.fire({
                    title: 'Menyimpan data...',
                    text: 'Mohon tunggu, sedang diproses',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(formData)
                })
                    .then(async res => {

                        let data = await res.json();

                        if (!res.ok) {
                            throw data;
                        }

                        return data;
                    })
                    .then(res => {

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });

                    })
                    .catch(err => {

                        let msg = 'Terjadi kesalahan';

                        // VALIDATION ERROR LARAVEL
                        if (err.errors) {
                            msg = Object.values(err.errors).map(e => e[0]).join('\n');
                        } else if (err.message) {
                            msg = err.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: msg
                        });

                        console.error(err);
                    })
                    .finally(() => {
                        btn.disabled = false;
                    });
            });

            // ================= DELETE =================
            document.querySelectorAll('.deleteBtn').forEach(btn => {
                btn.addEventListener('click', () => {

                    let id = btn.closest('tr').dataset.id;

                    Swal.fire({
                        title: 'Hapus user?',
                        text: 'Data tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true
                    }).then(res => {
                        if (res.isConfirmed) {

                            Swal.fire({
                                title: 'Menghapus...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });

                            fetch(`/admin/users/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
                            })
                                .then(r => r.json())
                                .then(r => {
                                    Swal.fire('Berhasil', r.message, 'success')
                                        .then(() => location.reload());
                                })
                                .catch(() => {
                                    Swal.fire('Error', 'Gagal menghapus user', 'error');
                                });
                        }
                    });
                });
            });

            // ================= TOGGLE =================
            document.querySelectorAll('.toggleBtn').forEach(btn => {
                btn.addEventListener('click', () => {

                    let id = btn.closest('tr').dataset.id;

                    Swal.fire({
                        title: 'Ubah status user?',
                        icon: 'question',
                        showCancelButton: true
                    }).then(res => {
                        if (res.isConfirmed) {

                            Swal.fire({
                                title: 'Memproses...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });

                            fetch(`/admin/users/${id}/toggle-active`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            })
                                .then(r => r.json())
                                .then(r => {
                                    Swal.fire('Berhasil', r.message, 'success')
                                        .then(() => location.reload());
                                })
                                .catch(() => {
                                    Swal.fire('Error', 'Gagal mengubah status', 'error');
                                });
                        }
                    });

                });
            });

        });
    </script>
@endsection