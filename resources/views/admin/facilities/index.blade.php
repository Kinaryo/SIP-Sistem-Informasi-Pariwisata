@extends('admin.layouts.app-admin')

@section('title', 'Kelola Fasilitas')
@section('page-title', 'Kelola Fasilitas')

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
            <h5 class="fw-bold mb-0">Daftar Fasilitas</h5>
            <small class="text-muted">Kelola fasilitas wisata</small>
        </div>

        <button class="btn btn-primary" onclick="openModal()">
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
                    <th width="100">Gambar</th>
                    <th width="160">Aksi</th>
                </tr>
            </thead>

            <tbody id="facility-table">
                @forelse($facilities as $facility)
                <tr id="facility-{{ $facility->id }}">

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <strong>{{ $facility->name }}</strong>
                    </td>

                    <td class="text-center">
                        @if($facility->image)
                            @php
                                $imgSrc = Str::startsWith($facility->image, ['http://', 'https://'])
                                    ? $facility->image
                                    : asset('storage/' . $facility->image);
                            @endphp
                            <img src="{{ $imgSrc }}"
                                 style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @else
                            <div style="width:50px;height:50px;background:#f1f1f1;border-radius:6px;"
                                 class="d-flex align-items-center justify-content-center">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif
                    </td>

                    <td>
                        <div class="d-flex gap-1">

                            <!-- EDIT -->
                            <button class="btn btn-sm btn-warning"
                                data-bs-toggle="tooltip"
                                title="Edit"
                                onclick="editFacility({{ $facility->id }})">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <!-- DELETE -->
                            <button class="btn btn-sm btn-danger"
                                data-bs-toggle="tooltip"
                                title="Hapus"
                                onclick="deleteFacility({{ $facility->id }})">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data fasilitas</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>
</div>
</div>

<!-- MODAL -->
<div class="modal fade" id="facilityModal" tabindex="-1">
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
                        <label class="form-label">Nama</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" id="image" name="image" class="form-control">
                        <small class="text-muted">Kosongkan jika tidak diubah</small>
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<script>

// ================= TOOLTIP =================
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
        new bootstrap.Tooltip(el)
    });
});

let facilityModal = new bootstrap.Modal(document.getElementById('facilityModal'));

// ================= LOADING =================
function showLoading(text = 'Memproses...') {
    Swal.fire({
        title: text,
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
}

function closeLoading() {
    Swal.close();
}

// ================= OPEN MODAL =================
function openModal() {
    document.getElementById('facilityForm').reset();
    document.getElementById('facilityId').value = '';
    document.getElementById('modalTitle').innerText = 'Tambah Fasilitas';
    facilityModal.show();
}

// ================= EDIT =================
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
        Swal.fire('Error','Gagal mengambil data','error');
    });
}

// ================= SAVE =================
document.getElementById('facilityForm').addEventListener('submit', function(e){
    e.preventDefault();

    showLoading('Menyimpan...');

    let id = document.getElementById('facilityId').value;
    let formData = new FormData(this);

    let url = id ? `/admin/facilities/${id}` : '/admin/facilities';
    if(id) formData.append('_method','PUT');

    fetch(url,{
        method:'POST',
        body:formData,
        headers:{
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res=>res.json())
    .then(res=>{
        closeLoading();
        facilityModal.hide();

        Swal.fire('Berhasil',res.message,'success')
        .then(()=>location.reload());
    })
    .catch(()=>{
        closeLoading();
        Swal.fire('Error','Terjadi kesalahan','error');
    });
});

// ================= DELETE =================
function deleteFacility(id){

    Swal.fire({
        title:'Hapus data?',
        text:'Data tidak bisa dikembalikan',
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'Ya',
        cancelButtonText:'Batal'
    }).then(result=>{

        if(result.isConfirmed){

            showLoading('Menghapus...');

            fetch(`/admin/facilities/${id}`,{
                method:'POST',
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body:new URLSearchParams({_method:'DELETE'})
            })
            .then(res=>res.json())
            .then(res=>{
                closeLoading();
                document.getElementById(`facility-${id}`).remove();

                Swal.fire('Berhasil',res.message,'success');
            })
            .catch(()=>{
                closeLoading();
                Swal.fire('Error','Gagal menghapus','error');
            });

        }
    });
}

</script>

@endpush