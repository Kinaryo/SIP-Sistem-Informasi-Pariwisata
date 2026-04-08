@extends('auth.layouts.app-auth')

@section('title', 'Login')

@section('content')

    <div class="col-md-5 col-lg-4">
        <div class="card shadow-lg auth-card">
            <div class="card-body p-4">

                <div class="text-center mb-4">
                    <a href="/">
                        <i class="fas fa-map-marked-alt text-primary fs-1 mb-2"></i>
                    </a>
                    <h4 class="fw-bold">Login</h4>
                    <p class="text-muted small">Masuk untuk mengelola atau menjelajah</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger small">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="form-control @error('email') is-invalid @enderror">
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    </div>


                    <button class="btn btn-primary w-100 py-2">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                </form>

                <hr>
                <div class="text-center mb-2 small">
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                </div>

                <div class="text-center small">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar</a>
                </div>

            </div>
        </div>
    </div>

@endsection