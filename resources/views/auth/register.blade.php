@extends('auth.layouts.app-auth')

@section('title', 'Register')

@section('content')

<div class="col-md-5 col-lg-4 p-4">
    <div class="card shadow-lg auth-card">
        <div class="card-body p-4">

            <div class="text-center mb-4">
                <a href="/">
                    <i class="fas fa-map-marked-alt text-primary fs-1 mb-2"></i>
                </a>
                <h4 class="fw-bold">Daftar</h4>
                <p class="text-muted small">Buat akun baru</p>
            </div>

            <form id="formRegister" method="POST" action="{{ route('register.process') }}">
                @csrf

                <div class="mb-3">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Alamat</label>
                    <textarea name="address" rows="2"
                        class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password</label>

                    <div class="input-group">
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror">

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror

                    <div class="mt-2 small" id="passwordRules">
                        <div id="rule-length" class="text-danger">
                            <i class="bi bi-x-circle"></i> Minimal 6 karakter
                        </div>
                        <div id="rule-upper" class="text-danger">
                            <i class="bi bi-x-circle"></i> Mengandung huruf besar
                        </div>
                        <div id="rule-lower" class="text-danger">
                            <i class="bi bi-x-circle"></i> Mengandung huruf kecil
                        </div>
                        <div id="rule-number" class="text-danger">
                            <i class="bi bi-x-circle"></i> Mengandung angka
                        </div>
                        <div id="rule-symbol" class="text-danger">
                            <i class="bi bi-x-circle"></i> Mengandung simbol
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Konfirmasi Password</label>

                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror">

                        <button type="button" class="btn btn-outline-secondary"
                            onclick="togglePassword('password_confirmation', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>

                    @error('password_confirmation')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button id="btnSubmit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-person-plus"></i> Daftar
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


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('formRegister');
    const btn = document.getElementById('btnSubmit');
    const passwordInput = document.getElementById('password');

    const rules = {
        length: document.getElementById('rule-length'),
        upper: document.getElementById('rule-upper'),
        lower: document.getElementById('rule-lower'),
        number: document.getElementById('rule-number'),
        symbol: document.getElementById('rule-symbol'),
    };

    form.addEventListener('submit', function () {
        btn.disabled = true;

        Swal.fire({
            title: 'Sedang memproses...',
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    });

    passwordInput.addEventListener('input', function () {
        const value = passwordInput.value;

        validateRule(value.length >= 6, rules.length);
        validateRule(/[A-Z]/.test(value), rules.upper);
        validateRule(/[a-z]/.test(value), rules.lower);
        validateRule(/[0-9]/.test(value), rules.number);
        validateRule(/[^A-Za-z0-9]/.test(value), rules.symbol);
    });

    function validateRule(condition, element) {
        const text = element.textContent.replace('✔', '').replace('✖', '').trim();

        if (condition) {
            element.classList.remove('text-danger');
            element.classList.add('text-success');
            element.innerHTML = '<i class="bi bi-check-circle"></i> ' + text;
        } else {
            element.classList.remove('text-success');
            element.classList.add('text-danger');
            element.innerHTML = '<i class="bi bi-x-circle"></i> ' + text;
        }
    }

    document.querySelector('.is-invalid')?.focus();

});

function togglePassword(fieldId, el) {
    const input = document.getElementById(fieldId);
    const icon = el.querySelector('i');

    if (!input) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '{{ session('success') }}'
});
@endif

@if ($errors->any())
Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: 'Periksa kembali input Anda'
});
@endif

</script>

@endsection