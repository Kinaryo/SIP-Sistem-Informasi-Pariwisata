@forelse($produks as $p)
    @php
        $nama = \Illuminate\Support\Str::limit($p->nama_produk, 45);
        $toko = $p->user->toko ?? null;
    @endphp

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-product">

            <div class="position-relative">
                <a href="{{ route('produk.show', $p->id) }}" class="text-decoration-none">
                    @if($p->foto)
                        <img src="{{ Str::startsWith($p->foto, 'http') ? $p->foto : asset('storage/' . $p->foto) }}"
                            class="card-img-top" style="height:220px; object-fit:cover;" alt="{{ $p->nama_produk }}">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light"
                            style="height:220px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                            <small class="text-muted">No Image</small>
                        </div>
                    @endif
                </a>
            </div>

            <div class="card-body p-4 d-flex flex-column">
                <div class="d-flex align-items-center gap-2 mb-2">
                    @if($toko && $toko->logo)
                        <img src="{{ $toko->logo }}" width="24" height="24" class="rounded-circle" style="object-fit:cover;">
                    @else
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                            style="width:24px; height:24px;">
                            <i class="bi bi-shop" style="font-size: 10px;"></i>
                        </div>
                    @endif
                    <span class="text-muted small fw-medium">{{ $toko->nama_toko ?? 'Tanpa Toko' }}</span>
                </div>

                <h6 class="card-title fw-bold mb-1">
                    <a href="{{ route('produk.show', $p->id) }}" class="text-dark text-decoration-none hover-primary">
                        {{ $nama }}
                    </a>
                </h6>

                <h5 class="text-primary fw-bold mb-3">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </h5>

                <div class="mt-auto pt-3 border-top d-flex gap-2">
                    <a href="{{ route('produk.show', $p->id) }}"
                        class="btn btn-light btn-sm flex-grow-1 fw-semibold rounded-3 py-2">
                        Detail
                    </a>

                    @if($toko && $toko->telepon_aktif && $toko->telepon)
                        <span class="d-inline-block" data-bs-toggle="tooltip" data-bs-title="Hubungi penjual sekarang">
                            <a href="https://wa.me/{{ $toko->telepon }}" target="_blank"
                                class="btn btn-success btn-sm px-3 rounded-3 py-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </span>
                    @else
                        <span class="d-inline-block" data-bs-toggle="tooltip"
                            data-bs-title="WhatsApp tidak tersedia / Toko tutup">
                            <button class="btn btn-light btn-sm px-3 rounded-3 py-2 text-muted border" disabled>
                                <i class="bi bi-whatsapp"></i>
                            </button>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

@empty
    <div class="col-12 py-5 text-center">
        <p class="text-muted">Wah, produk yang kamu cari tidak ditemukan.</p>
    </div>
@endforelse

<style>
    /* Efek Kartu Modern */
    .card-product {
        transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
    }

    .card-product:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
    }

    /* Warna Hover pada Judul */
    .hover-primary:hover {
        color: #0d6efd !important;
        /* Sesuaikan warna primary Anda */
    }

    /* Styling Custom Tooltip */
    .tooltip-inner {
        background-color: #212529 !important;
        padding: 6px 12px !important;
        border-radius: 6px !important;
        font-size: 12px !important;
    }

    /* Memastikan span pembungkus tooltip sejajar */
    .d-inline-block {
        vertical-align: middle;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>