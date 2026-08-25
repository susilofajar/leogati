<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori produk.
     */
    public function index(Request $request)
    {
        $query = Category::with(['parent'])->withCount('products');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($kategori) use ($q) {
                $kategori->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->orderBy('parent_id')->orderBy('sort_order')->paginate(15)->withQueryString();

        return view('admin.kategori.index', compact('categories'));
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.kategori.create', compact('parentCategories'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:categories,slug',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $category = Category::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'parent_id'   => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        AuditLogService::log(
            'create_category',
            'Category',
            $category->id,
            $category->toArray()
        );

        return redirect()->route('admin.kategori.index')
            ->with('success', "Kategori '{$category->name}' berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(Category $kategori)
    {
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $kategori->id)
            ->orderBy('name')
            ->get();

        return view('admin.kategori.edit', [
            'category'         => $kategori,
            'parentCategories' => $parentCategories,
        ]);
    }

    /**
     * Perbarui kategori.
     */
    public function update(Request $request, Category $kategori)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($kategori->id)],
            'parent_id'   => ['nullable', 'exists:categories,id', Rule::notIn([$kategori->id])],
            'description' => 'nullable|string',
            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);

        $kategori->update([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'parent_id'   => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? 0,
            'is_active'   => $request->has('is_active'),
        ]);

        AuditLogService::log(
            'update_category',
            'Category',
            $kategori->id,
            $kategori->toArray()
        );

        return redirect()->route('admin.kategori.index')
            ->with('success', "Kategori '{$kategori->name}' berhasil diperbarui.");
    }

    /**
     * Hapus kategori (dengan proteksi produk dan anak kategori).
     */
    public function destroy(Category $kategori)
    {
        if ($kategori->products()->count() > 0) {
            return back()->with('error', "Kategori '{$kategori->name}' tidak dapat dihapus karena masih memiliki {$kategori->products()->count()} produk terkait.");
        }

        if ($kategori->children()->count() > 0) {
            return back()->with('error', "Kategori '{$kategori->name}' tidak dapat dihapus karena memiliki sub-kategori aktif.");
        }

        $id = $kategori->id;
        $name = $kategori->name;
        $kategori->delete();

        AuditLogService::log(
            'delete_category',
            'Category',
            $id,
            ['name' => $name]
        );

        return redirect()->route('admin.kategori.index')
            ->with('success', "Kategori '{$name}' berhasil dihapus.");
    }
}
