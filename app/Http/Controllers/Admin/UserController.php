<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    // ================= VALIDATION MESSAGE =================
    private function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',

            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',

            'address.required' => 'Alamat wajib diisi',
            'address.max' => 'Alamat maksimal 500 karakter',

            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
        ];
    }

    // ================= RESPONSE HELPER =================
    private function success($message, $data = [])
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    private function error($message, $errors = [], $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $code);
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:6',
        ], $this->messages());

        if ($validator->fails()) {
            return $this->error('Validasi gagal', $validator->errors(), 422);
        }

        try {

            $data = $validator->validated();
            $data['password'] = Hash::make($data['password']);
            $data['is_active'] = true;

            User::create($data);

            return $this->success('User berhasil ditambahkan');

        } catch (\Exception $e) {

            return $this->error('Terjadi kesalahan saat menyimpan data', [], 500);
        }
    }

    // ================= UPDATE =================
    public function update(Request $request, User $user)
    {
        $messages = $this->messages();
        unset($messages['password.required']); // password opsional saat update

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'user'])],
            'address' => 'required|string|max:500',
            'password' => 'nullable|string|min:6',
        ], $messages);

        if ($validator->fails()) {
            return $this->error('Validasi gagal', $validator->errors(), 422);
        }

        try {

            $data = $validator->validated();

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $user->update($data);

            return $this->success('User berhasil diperbarui');

        } catch (\Exception $e) {

            return $this->error('Terjadi kesalahan saat update data', [], 500);
        }
    }

    // ================= DELETE =================
    public function destroy(User $user)
    {
        try {

            $user->delete();

            return $this->success('User berhasil dihapus');

        } catch (\Exception $e) {

            return $this->error('Gagal menghapus user', [], 500);
        }
    }

    // ================= TOGGLE =================
    public function toggleActive($id)
    {
        try {

            $user = User::findOrFail($id);

            $user->is_active = !$user->is_active;
            $user->save();

            return $this->success(
                $user->is_active
                    ? 'User berhasil diaktifkan'
                    : 'User berhasil dinonaktifkan',
                ['status' => $user->is_active]
            );

        } catch (\Exception $e) {

            return $this->error('Gagal mengubah status user', [], 500);
        }
    }
}