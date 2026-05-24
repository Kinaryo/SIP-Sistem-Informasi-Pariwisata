<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->email))
        ]);

        $request->validate([
            'email'    => 'required|email:rfc,dns',
            'password' => 'required'
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Login berhasil sebagai admin');
            }

            return redirect()->route('landing')
                ->with('success', 'Selamat datang ' . $user->name);
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah'
            ])
            ->withInput();
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->email))
        ]);

        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email:rfc,dns|unique:users,email',
            'address'  => 'required|string',
            'password' => [
                'required',
                'min:6',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&^#()_\-+=]/',
            ],
        ], [
            'name.required'      => 'Nama wajib diisi',
            'name.max'           => 'Nama maksimal 100 karakter',
            'email.required'     => 'Email wajib diisi',
            'email.email'        => 'Format email tidak valid',
            'email.unique'       => 'Email sudah terdaftar',
            'address.required'   => 'Alamat wajib diisi',
            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'password.regex'     => 'Password harus mengandung huruf besar, huruf kecil, angka, dan simbol',
        ]);

        User::create([
            'name'     => trim($request->name),
            'email'    => trim($request->email),
            'address'  => $request->address,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil, silakan login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')
            ->with('success', 'Berhasil logout');
    }
}