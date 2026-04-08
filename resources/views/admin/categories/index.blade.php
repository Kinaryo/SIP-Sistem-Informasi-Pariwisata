@extends('admin.layouts.app-admin')

@section('title', 'Master Kategori')
@section('page-title', 'Master Data Kategori')

@section('content')

<style>
.pagination { gap: 6px; }

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
</style>

<div class="row g-4">
<div class="col-md-12">

<div class="card border-0 shadow-sm rounded-4 p-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">Daftar Kategori</h5>
            <small class="text-muted">Kelola kategori wisata</small>
        </div>

        <button class="btn btn-primary"
            onclick="openCreate()"
            data-bs-toggle="tooltip"
            title="Tambah Kategori">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th width="60">#</th>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Deskripsi</th>
                    <th width="140">Aksi</th>
                </tr>
            </thead>

            <tbody id="categoryTable">
                @forelse ($categories as $category)
                <tr id="row-{{ $category->id }}">

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <strong>{{ $category->name }}</strong>
                    </td>

                    <td>
                        <small class="text-muted">{{ $category->slug }}</small>
                    </td>

                    <td>
                        {{ $category->description ?? '-' }}
                    </td>

                    <td>
                        <div class="d-flex gap-1">

                            <!-- EDIT -->
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="tooltip"
                                title="Edit"
                                onclick="editCategory({{ $category->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <!-- DELETE -->
                            <button class="btn btn-sm btn-danger"
                                data-bs-toggle="tooltip"
                                title="Hapus"
                                onclick="deleteCategory({{ $category->id }})">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Tidak ada data kategori
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
</div>
</div>


<!-- MODAL -->
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
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>

            </div>
        </form>
    </div>
</div>

@endsection


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>

// ================= TOOLTIP =================
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el)
    });
});

const modal = new bootstrap.Modal(document.getElementById('categoryModal'));

// ================= CREATE =================
function openCreate() {
    document.getElementById('categoryForm').reset();
    document.getElementById('category_id').value = '';
    document.getElementById('method').value = 'POST';
    document.getElementById('modalTitle').innerText = 'Tambah Kategori';
    modal.show();
}

// ================= EDIT =================
function editCategory(id) {

    Swal.fire({
        title:'Loading...',
        allowOutsideClick:false,
        didOpen:()=>Swal.showLoading()
    });

    fetch(`/admin/categories/${id}`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {

        Swal.close();

        document.getElementById('category_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('description').value = data.description ?? '';
        document.getElementById('method').value = 'PUT';
        document.getElementById('modalTitle').innerText = 'Edit Kategori';

        modal.show();
    })
    .catch(() => {
        Swal.fire('Error','Gagal mengambil data','error');
    });
}

// ================= SUBMIT =================
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title:'Menyimpan...',
        allowOutsideClick:false,
        didOpen:()=>Swal.showLoading()
    });

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

        Swal.close();

        if (res.errors) {
            Swal.fire({
                icon:'error',
                title:'Gagal',
                text:Object.values(res.errors)[0][0]
            });
            return;
        }

        modal.hide();

        Swal.fire({
            icon:'success',
            title:'Berhasil',
            text:res.message,
            timer:1500,
            showConfirmButton:false
        }).then(()=>location.reload());
    })
    .catch(()=>{
        Swal.fire('Error','Terjadi kesalahan server','error');
    });
});

// ================= DELETE =================
function deleteCategory(id) {

    Swal.fire({
        title:'Hapus data?',
        text:'Data tidak bisa dikembalikan',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Ya',
        cancelButtonText:'Batal'
    }).then(result => {

        if(result.isConfirmed){

            Swal.fire({
                title:'Menghapus...',
                allowOutsideClick:false,
                didOpen:()=>Swal.showLoading()
            });

            fetch(`/admin/categories/${id}`, {
                method:'DELETE',
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept':'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {

                Swal.close();

                document.getElementById(`row-${id}`).remove();

                Swal.fire('Berhasil', res.message, 'success');
            })
            .catch(()=>{
                Swal.fire('Error','Gagal menghapus','error');
            });
        }
    });
}

</script>

@endpush