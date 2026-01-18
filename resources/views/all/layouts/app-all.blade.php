<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jelajahi.my.id - Sistem Informasi Pariwisata</title>
    <link rel="icon" type="image/png" href="{{ asset('jelajahimyid.png') }}">


    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- FONT AWESOME -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-section {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, .5), rgba(0, 0, 0, .5)),
                url('https://images.unsplash.com/photo-1505993597083-3bd19fb75e57');
            background-size: cover;
            background-position: center;
        }

        .navbar-blur {
            backdrop-filter: blur(8px);
            background-color: rgba(255, 255, 255, .9);
        }

        .destination-card {
            transition: all .3s ease;
        }

        .destination-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
        }

        .destination-img {
            transition: transform .6s ease;
        }

        .destination-card:hover .destination-img {
            transform: scale(1.08);
        }
    </style>
</head>


<body class="bg-light text-dark">

    <div class="content">
        @include('all.partials.navbar')

        <main class="mt-4">
            @yield('content')
        </main>

        @include('all.partials.footer')
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>