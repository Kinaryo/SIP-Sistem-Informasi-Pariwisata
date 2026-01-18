@extends('admin.layouts.app-admin')

@section('title', 'Master Kategori')
@section('page-title', 'Master Data Kategori')

@section('content')

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Daftar Kategori</h5>
            <button class="btn btn-primary btn-sm" onclick="openCreate()">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Deskripsi</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody id="categoryTable">
                    @foreach ($categories as $category)
                        <tr id="row-{{ $category->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->description }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    onclick="editCategory({{ $category->id }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-sm"
                                    onclick="deleteCategory({{ $category->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="categoryForm">
            @csrf
            <input type="hidden" id="method">
            <input type="hidden" id="category_id">

            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary btn-sm" type="submit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const modal = new bootstrap.Modal(document.getElementById('categoryModal'));

    function openCreate() {
        document.getElementById('categoryForm').reset();
        document.getElementById('category_id').value = '';
        document.getElementById('method').value = 'POST';
        document.getElementById('modalTitle').innerText = 'Tambah Kategori';
        modal.show();
    }

    function editCategory(id) {
        fetch(`/admin/categories/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('category_id').value = data.id;
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description ?? '';
            document.getElementById('method').value = 'PUT';
            document.getElementById('modalTitle').innerText = 'Edit Kategori';
            modal.show();
        });
    }

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let id = document.getElementById('category_id').value;
        let method = document.getElementById('method').value;
        let url = '/admin/categories' + (method === 'PUT' ? '/' + id : '');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': method,
                'Accept': 'application/json'
            },
            body: new FormData(this)
        })
        .then(res => res.json())
        .then(res => {
            if (res.errors) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: Object.values(res.errors)[0][0]
                });
                return;
            }

            modal.hide();

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: res.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        })
        .catch(() => {
            Swal.fire('Error', 'Terjadi kesalahan server', 'error');
        });
    });

    function deleteCategory(id) {
        Swal.fire({
            title: 'Yakin?',
            text: 'Kategori akan dihapus permanen',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/admin/categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    document.getElementById(`row-${id}`).remove();
                    Swal.fire('Berhasil', res.message, 'success');
                })
                .catch(() => {
                    Swal.fire('Error', 'Gagal menghapus data', 'error');
                });
            }
        });
    }
</script>
@endpush
