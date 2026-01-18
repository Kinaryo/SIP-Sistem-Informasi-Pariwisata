@extends('admin.layouts.app-admin')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
    <div class="row g-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="mb-1">Daftar User</h5>
                <div class="d-flex justify-content-end mb-2">
                    <button class="btn btn-sm btn-primary d-flex align-items-center" id="addUserBtn">
                        <i class="bi bi-plus-lg me-1"></i>
                        Tambah
                    </button>
                </div>


                <table class="table table-bordered">
                    <thead class="table-light text-center">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Alamat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr data-id="{{ $user->id }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">{{ $user->role }}</td>
                                <td>{{ $user->address }}</td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button
                                            class="btn btn-sm btn-warning editBtn d-flex align-items-center justify-content-center">
                                            <i class="bi bi-pencil-fill"></i>
                                        </button>
                                        <button
                                            class="btn btn-sm btn-danger deleteBtn d-flex align-items-center justify-content-center">
                                            <i class="bi bi-trash-fill"></i>
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

    <!-- Modal Form User -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <h5 id="modalTitle"></h5>
                <form id="userForm">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Alamat</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success d-flex align-items-center justify-content-center"
                            id="submitBtn">
                            <i class="bi bi-check-lg me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const userModal = new bootstrap.Modal(document.getElementById('userModal'));
        const userForm = document.getElementById('userForm');
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');

        document.getElementById('addUserBtn').addEventListener('click', () => {
            modalTitle.textContent = 'Tambah User';
            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            userForm.reset();
            document.getElementById('formMethod').value = 'POST';
            userModal.show();
        });

        // Edit user
        document.querySelectorAll('.editBtn').forEach(btn => {
            btn.addEventListener('click', e => {
                const tr = e.target.closest('tr');
                const id = tr.dataset.id;
                modalTitle.textContent = 'Edit User';
                submitBtn.innerHTML = '<i class="bi bi-pencil-fill me-1"></i> Update';
                document.getElementById('formMethod').value = 'PUT';

                userForm.name.value = tr.children[1].textContent;
                userForm.email.value = tr.children[2].textContent;
                userForm.role.value = tr.children[3].textContent;
                userForm.address.value = tr.children[4].textContent;
                userForm.password.value = '';

                userForm.dataset.id = id;
                userModal.show();
            });
        });

        // Hapus  user
        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', e => {
                const tr = e.target.closest('tr');
                const id = tr.dataset.id;
                Swal.fire({
                    title: 'Yakin ingin menghapus user?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Menghapus'
                }).then(result => {
                    if (result.isConfirmed) {
                        fetch(`/admin/users/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            }
                        })
                            .then(res => res.json())
                            .then(json => {
                                Swal.fire('Berhasil', json.message, 'success');
                                tr.remove();
                            })
                            .catch(err => {
                                Swal.fire('Error', 'Terjadi kesalahan', 'error');
                            });
                    }
                });
            });
        });

        // Submit form add/edit user
        userForm.addEventListener('submit', e => {
            e.preventDefault();
            const formData = new FormData(userForm);
            const data = {};
            formData.forEach((v, k) => data[k] = v);

            let id = userForm.dataset.id;
            let method = document.getElementById('formMethod').value;
            let url = method === 'POST' ? '/admin/users' : `/admin/users/${id}`;

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(data)
            })
                .then(async res => {
                    const json = await res.json();
                    if (res.ok) {
                        Swal.fire('Berhasil', json.message, 'success').then(() => location.reload());
                    } else {
                        let errorMsg = json.message;
                        if (json.errors) {
                            errorMsg = Object.values(json.errors).flat().join('<br>');
                        }
                        throw new Error(errorMsg);
                    }
                })
                .catch(err => Swal.fire('Gagal', err.message, 'error'));
        });
    </script>
@endpush