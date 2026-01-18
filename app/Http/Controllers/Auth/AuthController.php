<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /* =====================
        FORM LOGIN
    ====================== */
    public function loginForm()
    {
        return view('auth.login');
    }

    /* =====================
        PROSES LOGIN
    ====================== */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('landing');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    /* =====================
        FORM REGISTER
    ====================== */
    public function registerForm()
    {
        return view('auth.register');
    }

    /* =====================
        PROSES REGISTER
    ====================== */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'address'  => 'required|string',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'address'  => $data['address'],
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil.');
    }

    /* =====================
        LOGOUT
    ====================== */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing');
    }
}
