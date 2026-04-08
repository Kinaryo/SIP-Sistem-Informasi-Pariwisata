@forelse($produks as $p)
    @php
        $nama = \Illuminate\Support\Str::limit($p->nama_produk, 45);

        // Ambil label toko/admin
        $label = $p->toko_label ?? 'Tanpa Toko';

        // Generate inisial (max 2 huruf)
        $inisial = collect(explode(' ', $label))
            ->filter()
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->join('');

        // Fallback jika kosong
        if (!$inisial) {
            $inisial = 'X';
        }

        // Warna random tapi konsisten
        $colors = ['#667eea','#764ba2','#f093fb','#f5576c','#4facfe','#43e97b'];
        $bg = $colors[crc32($label) % count($colors)];
    @endphp

    <div class="col-md-6 col-lg-3 mb-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-product">

            {{-- IMAGE --}}
            <div class="position-relative">
                <a href="{{ route('produk.show', $p->id) }}" class="text-decoration-none">
                    @if($p->foto)
                        <img src="{{ \Illuminate\Support\Str::startsWith($p->foto, 'http') ? $p->foto : asset('storage/' . $p->foto) }}"
                             class="card-img-top"
                             style="height:220px; object-fit:cover;"
                             alt="{{ $p->nama_produk }}">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center bg-light"
                             style="height:220px;">
                            <i class="bi bi-image text-muted fs-1"></i>
                            <small class="text-muted">No Image</small>
                        </div>
                    @endif
                </a>
            </div>

            {{-- BODY --}}
            <div class="card-body p-4 d-flex flex-column">

                {{-- TOKO / ADMIN --}}
                <div class="d-flex align-items-center gap-2 mb-2">

                    @if($p->toko_logo)
                        <img src="{{ $p->toko_logo }}"
                             width="24" height="24"
                             class="rounded-circle"
                             style="object-fit:cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                             style="width:24px; height:24px; font-size:10px; background: {{ $bg }};">
                            {{ $inisial }}
                        </div>
                    @endif

                    <span class="text-muted small fw-medium">
                        {{ $label }}
                    </span>
                </div>

                {{-- NAMA PRODUK --}}
                <h6 class="card-title fw-bold mb-1">
                    <a href="{{ route('produk.show', $p->id) }}"
                       class="text-dark text-decoration-none hover-primary">
                        {{ $nama }}
                    </a>
                </h6>

                {{-- HARGA --}}
                <h5 class="text-primary fw-bold mb-3">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </h5>

                {{-- ACTION --}}
                <div class="mt-auto pt-3 border-top d-flex gap-2">
                    <a href="{{ route('produk.show', $p->id) }}"
                       class="btn btn-light btn-sm flex-grow-1 fw-semibold rounded-3 py-2">
                        Detail
                    </a>

                    @if($p->toko_telepon)
                        <span class="d-inline-block" data-bs-toggle="tooltip" data-bs-title="Hubungi penjual sekarang">
                            <a href="https://wa.me/{{ $p->toko_telepon }}" target="_blank"
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