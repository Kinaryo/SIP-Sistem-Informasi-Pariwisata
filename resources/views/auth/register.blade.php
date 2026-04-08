@extends('auth.layouts.app-auth')

@section('title', 'Register')

@section('content')

<div class="col-md-5 col-lg-4">
    <div class="card shadow-lg auth-card">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <a href="/">
                    <i class="fas fa-map-marked-alt text-primary fs-1 mb-2"></i>
                </a>
                <h4 class="fw-bold">Daftar</h4>
                <p class="text-muted small">Buat akun baru</p>
            </div>


            <form method="POST" action="{{ route('register.process') }}">
                @csrf

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror">
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="address" rows="2"
                        class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror">
                </div>

                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button class="btn btn-primary w-100 py-2">
                    <i class="fas fa-user-plus"></i> Daftar
                </button>
            </form>

            <hr>

            <div class="text-center small">
                Sudah punya akun?
                <a href="{{ route('login') }}">Login</a>
            </div>

        </div>
    </div>
</div>

@endsection