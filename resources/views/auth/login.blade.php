<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login | jelajahi.my.id</title>
    <link rel="icon" type="image/png" href="{{ asset('jelajahimyid.png') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            min-height: 100vh;
        }

        .auth-card {
            border-radius: 1rem;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center">

    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg auth-card">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <a href="/">
                        <i class="fas fa-map-marked-alt text-primary fs-1 mb-2"></i>
                    </a>
                    <h4 class="fw-bold">Login <br></h4>
                    <p class="text-muted small">Masuk untuk mengelola atau menjelajah wisata</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger small">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror" placeholder="email@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- BUTTON -->
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>

                <hr>

                <div class="text-center small">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                        Daftar sekarang
                    </a>
                </div>

            </div>
        </div>
    </div>

</body>

</html>