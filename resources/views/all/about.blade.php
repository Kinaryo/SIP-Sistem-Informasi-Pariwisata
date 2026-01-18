@extends('all.layouts.app-all')

@section('title', 'Landing Page')

@section('content')

   <!-- ================= TENTANG KAMI ================= -->
    <section id="tentang" class="py-5" style="background:linear-gradient(135deg,#f8f9fa,#eef4ff);">
        <div class="container">

            <div class="text-center mb-5">
                <h2 class="fw-bold display-6 text-dark">Tentang Kami</h2>
                <p class="text-muted mt-3">
                    Platform informasi pariwisata terpercaya dari wilayah timur Indonesia
                </p>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <h5 class="fw-semibold mb-3">Pesona Wisata Indonesia</h5>
                    <p class="text-muted">
                        Kami adalah platform sistem informasi pariwisata yang bertujuan untuk
                        memperkenalkan keindahan alam, budaya, dan destinasi unggulan Indonesia,
                        khususnya dari wilayah timur seperti Papua dan Maluku.
                    </p>

                    <p class="text-muted">
                        Melalui website ini, kami ingin membantu wisatawan menemukan destinasi terbaik,
                        informasi lokasi, serta gambaran wisata yang akurat dan mudah diakses.
                    </p>

                    <ul class="list-unstyled text-muted mt-3">
                        <li>✔ Informasi destinasi terpercaya</li>
                        <li>✔ Data lokasi & peta wisata</li>
                        <li>✔ Mendukung promosi wisata lokal</li>
                        <li>✔ 100% Geratis</li>
                    </ul>
                </div>

                <div class="col-md-6 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/af/Taman_0_Kilometer_Merauke_-_Sabang.jpg/960px-Taman_0_Kilometer_Merauke_-_Sabang.jpg"
                        class="img-fluid rounded-4 shadow" alt="Tentang Kami">
                </div>
            </div>
        </div>
    </section>




@endsection