{{-- MODAL EDIT TOKO --}}
<div class="modal fade" id="modal-edit-toko" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="form-edit-toko" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Edit Toko</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Toko</label>
                        <input type="text" name="nama_toko" id="edit-nama" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" id="edit-deskripsi" class="form-control rounded-3"
                            rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Telepon</label>
                            <input type="text" name="telepon" id="edit-telepon" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Logo Baru (Opsional)</label>
                            <input type="file" name="logo" class="form-control rounded-3" accept="image/*">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="telepon_aktif" value="1"
                            id="edit-telp-aktif">
                        <label class="form-check-label small" for="edit-telp-aktif">Nomor telepon aktif/tampil</label>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4">Update Toko</button>
                </div>
            </form>
        </div>
    </div>
</div>