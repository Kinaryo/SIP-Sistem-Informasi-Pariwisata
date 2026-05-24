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

            <form id="formLogin" method="POST" action="{{ route('login.process') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror">

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label>Password</label>

                    <div class="input-group">
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button id="btnSubmit" class="btn btn-primary w-100 py-2">
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


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const form = document.getElementById('formLogin');
    const btn = document.getElementById('btnSubmit');


    form.addEventListener('submit', function() {
        btn.disabled = true;

        Swal.fire({
            title: 'Sedang login...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });


    function togglePassword(fieldId, el) {
        const input = document.getElementById(fieldId);
        const icon = el.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }


    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3085d6'
        });
    @endif


    @if ($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal!',
            text: '{{ $errors->first() }}',
            confirmButtonColor: '#d33'
        });

        document.querySelector('.is-invalid')?.focus();
    @endif

</script>

@endsection