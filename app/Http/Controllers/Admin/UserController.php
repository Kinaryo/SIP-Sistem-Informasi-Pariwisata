<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index()
    {
        $users = User::all(); // ambil semua user
        return view('admin.users.index', compact('users'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['admin','user'])],
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return response()->json([
            'message' => 'User berhasil ditambahkan'
        ]);
    }

    // Update data user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin','user'])],
            'address' => 'required|string|max:500',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Data user berhasil diperbarui'
        ]);
    }

    // Nonaktifkan / hapus user
    public function destroy(User $user)
    {
        $user->delete(); // Bisa diganti soft delete kalau ingin
        return response()->json([
            'message' => 'User berhasil dinonaktifkan'
        ]);
    }
}
