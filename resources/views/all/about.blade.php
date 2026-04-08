@extends('all.layouts.app-all')

@section('title', 'Landing Page')

@section('content')

   <!-- ================= TENTANG KAMI ================= -->
    <section id="tentang" class="py-5" style="background:linear-gradient(135deg,#f8f9fa,#eef4ff);">
       <div class="container">

            <div class="card border-0 shadow-lg rounded-4 p-4 p-md-5">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Tentang Kami</h2>
                <p class="text-muted mt-3">
                   Platform informasi digital tentang Merauke
                </p>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-md-6">
                                       <h5 class="fw-semibold mb-3">Tentang VisitMerauke</h5>
                    <p class="text-muted" style="text-align: justify">
                        VisitMerauke.com adalah platform sistem informasi yang menghadirkan berbagai informasi penting
                        mengenai Merauke, Papua Selatan. Kami tidak hanya berfokus pada pariwisata, tetapi juga
                        memperkenalkan budaya, produk lokal, serta potensi daerah yang dimiliki Merauke.
                    </p>

                    <p class="text-muted" style="text-align: justify">
                        Melalui platform ini, masyarakat dan wisatawan dapat dengan mudah mengakses informasi destinasi,
                        UMKM lokal, artikel, serta berbagai hal menarik yang ada di Merauke dalam satu tempat.
                    </p>

                    <ul class="list-unstyled text-muted mt-3">
                        <li>✔ Informasi destinasi wisata & budaya Merauke</li>
                        <li>✔ Direktori UMKM & produk lokal</li>
                        <li>✔ Artikel & wawasan daerah</li>
                        <li>✔ Mendukung promosi dan digitalisasi Merauke</li>
                    </ul>
                </div>

                <div class="col-md-6 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Taman_0_Kilometer_Merauke_-_Sabang.jpg/960px-Taman_0_Kilometer_Merauke_-_Sabang.jpg"
                        class="img-fluid rounded-4 shadow" alt="Tentang Kami">
                </div>
            </div>
        </div>
        </div>
    </section>




@endsection