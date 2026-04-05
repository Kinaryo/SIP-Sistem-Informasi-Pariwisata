{{-- MODAL TAMBAH TOKO --}}
<div class="modal fade" id="modal-tambah-toko" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form id="form-tambah-toko" enctype="multipart/form-data">
                @csrf
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Buat Toko Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Toko</label>
                        <input type="text" name="nama_toko" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control rounded-3" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Telepon</label>
                            <input type="text" name="telepon" class="form-control rounded-3">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Logo</label>
                            <input type="file" name="logo" class="form-control rounded-3" accept="image/*">
                        </div>
                    </div>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="telepon_aktif" value="1"
                            id="tambah-telp-aktif" checked>
                        <label class="form-check-label small" for="tambah-telp-aktif">Nomor telepon aktif/tampil</label>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Toko</button>
                </div>
            </form>
        </div>
    </div>
</div>
