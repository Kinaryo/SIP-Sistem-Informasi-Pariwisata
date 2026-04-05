{{-- CARD TOKO --}}
<div id="card-toko" class="mb-4">
    <div class="card shadow-sm rounded-4 p-4 border-0">
        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">

            {{-- LEFT --}}
            <div class="d-flex align-items-center flex-column flex-md-row text-center text-md-start">

                {{-- LOGO / ICON --}}
                <div id="wrapper-logo" class="mb-3 mb-md-0 me-md-3">
                    <img id="toko-logo" class="rounded shadow-sm d-none"
                        style="width:80px;height:80px;object-fit:cover;">

                    {{-- fallback icon --}}
                    <div id="toko-logo-icon"
                        class="d-flex align-items-center justify-content-center bg-light rounded shadow-sm"
                        style="width:80px;height:80px;">
                        <i class="bi bi-shop fs-2 text-secondary"></i>
                    </div>
                </div>

                {{-- TEXT --}}
                <div>
                    <h5 class="fw-bold mb-1" id="toko-nama">
                        Memuat data toko...
                    </h5>

                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                        <span class="text-muted d-flex align-items-center" style="font-size:0.85rem;">
                            <strong>Deskripsi Toko : </strong>
                            <span class="ms-2" id="toko-deskripsi">
                                -
                            </span>
                        </span>
                    </div>


                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                        <span class="text-muted d-flex align-items-center" style="font-size:0.85rem;">
                            <strong>Nomor Kontak Whatsapp : </strong>
                            <span class="ms-2"  id="toko-telepon">-</span>
                        </span>
                    </div>

                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2">
                        <span class="text-muted d-flex align-items-center" style="font-size:0.85rem;">
                            <strong>Status Kontak Ditampilkan : </strong>
                            <span id="badge-telepon" class="badge bg-light text-dark border" style="font-size: 0.65rem">
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- RIGHT BUTTON --}}
            <div class="mt-2 mt-md-0">
                <button class="btn btn-sm btn-primary px-3 rounded d-none" id="btn-buat-toko">
                    <i class="bi bi-shop me-1"></i> Buat Toko
                </button>

                <button class="btn btn-sm btn-warning px-3 rounded d-none" id="btn-edit-toko">
                    <i class="bi bi-pencil-square me-1"></i> Edit Toko
                </button>
            </div>

        </div>
    </div>
</div>