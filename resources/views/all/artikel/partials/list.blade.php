<style>
    .highlight {
        background-color: #0d6efd;
        color: #fff;
        padding: 2px 4px;
        border-radius: 4px;
    }

    /*  Limit baris */
    .title-limit {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .text-limit {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

@php
    $keyword = request('search');
@endphp

@forelse($artikels as $artikel)

    @php
        //  Clean isi dulu
        $isiRaw = preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($artikel->isi)));

        //  Limit karakter (lebih pendek biar aman saat highlight)
        $judul = \Illuminate\Support\Str::limit($artikel->judul, 25);
        $cleanIsi = \Illuminate\Support\Str::limit($isiRaw, 120);

        //  Highlight
        if ($keyword) {
            $pattern = '/' . preg_quote($keyword, '/') . '/i';

            $judul = preg_replace($pattern, '<span class="highlight">$0</span>', $judul);
            $cleanIsi = preg_replace($pattern, '<span class="highlight">$0</span>', $cleanIsi);
        }
    @endphp

    <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 card-hover">

            <!-- Gambar -->
            @if($artikel->gambar)
                <img src="{{ \Illuminate\Support\Str::startsWith($artikel->gambar, 'http')
                    ? $artikel->gambar
                    : asset('storage/' . $artikel->gambar) }}" class="card-img-top rounded-top-4"
                    style="height:200px; object-fit:cover;">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light rounded-top-4" style="height:200px;">
                    <small class="text-muted">Tidak ada gambar</small>
                </div>
            @endif

            <div class="card-body d-flex flex-column">

                <!-- Judul -->
                <h5 class="fw-semibold title-limit">
                    {!! $judul !!}
                </h5>

                <!-- Penulis -->
                <p class="small text-muted mb-1">
                    {{ $artikel->user->name ?? 'Admin' }}
                </p>

                <!-- Isi -->
                <p class="text-muted flex-grow-1 text-limit">
                    {!! $cleanIsi !!}
                </p>

                <!-- Tombol -->
                <a href="{{ route('artikel.show', $artikel->slug) }}" class="btn btn-outline-primary btn-sm mt-2">
                    Baca Selengkapnya →
                </a>

            </div>

        </div>
    </div>

@empty
    <div class="text-center text-muted">
        Tidak ditemukan artikel
    </div>
@endforelse