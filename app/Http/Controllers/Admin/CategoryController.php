<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * ===============================
     * INDEX
     * ===============================
     */
    public function index()
    {
        Log::info('Akses halaman master kategori');

        $categories = Category::latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * ===============================
     * STORE
     * ===============================
     */
    public function store(Request $request)
    {
        Log::info('Proses tambah kategori dimulai', $request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi gagal tambah kategori', $validator->errors()->toArray());

            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slug = $this->generateUniqueSlug($request->name);

            $category = Category::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description
            ]);

            Log::info('Kategori berhasil ditambahkan', [
                'id' => $category->id,
                'slug' => $category->slug
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $category
            ]);

        } catch (\Throwable $e) {

            Log::error('Gagal menambahkan kategori', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * ===============================
     * SHOW (AJAX EDIT)
     * ===============================
     */
    public function show(Category $category)
    {
        Log::info('Ambil data kategori', ['id' => $category->id]);

        return response()->json($category);
    }

    /**
     * ===============================
     * UPDATE
     * ===============================
     */
    public function update(Request $request, Category $category)
    {
        Log::info('Proses update kategori', [
            'id' => $category->id,
            'request' => $request->all()
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            Log::warning('Validasi gagal update kategori', $validator->errors()->toArray());

            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $slug = $this->generateUniqueSlug($request->name, $category->id);

            $category->update([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description
            ]);

            Log::info('Kategori berhasil diperbarui', [
                'id' => $category->id,
                'slug' => $slug
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil diperbarui'
            ]);

        } catch (\Throwable $e) {

            Log::error('Gagal update kategori', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * ===============================
     * DELETE
     * ===============================
     */
    public function destroy(Category $category)
    {
        Log::info('Proses hapus kategori', ['id' => $category->id]);

        try {
            $category->delete();

            Log::info('Kategori berhasil dihapus', ['id' => $category->id]);

            return response()->json([
                'status' => true,
                'message' => 'Kategori berhasil dihapus'
            ]);

        } catch (\Throwable $e) {

            Log::error('Gagal hapus kategori', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Kategori gagal dihapus'
            ], 500);
        }
    }

    /**
     * ===============================
     * HELPER: UNIQUE SLUG
     * ===============================
     */
    private function generateUniqueSlug(string $name, int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
