<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCategoryRequest;
use App\Http\Requests\Admin\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori produk.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

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
        $this->authorize('create', Category::class);

        $parentCategories = Category::whereNull('parent_id')->orderBy('name')->get();
        $availableIcons = Category::getAvailableIcons();
        return view('admin.kategori.create', compact('parentCategories', 'availableIcons'));
    }

    /**
     * Simpan kategori baru.
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validated();

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
            'icon'        => $validated['icon'] ?? null,
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
        $this->authorize('update', $kategori);

        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $kategori->id)
            ->orderBy('name')
            ->get();
        $availableIcons = Category::getAvailableIcons();

        return view('admin.kategori.edit', [
            'category'         => $kategori,
            'parentCategories' => $parentCategories,
            'availableIcons'   => $availableIcons,
        ]);
    }

    /**
     * Perbarui kategori.
     */
    public function update(UpdateCategoryRequest $request, Category $kategori)
    {
        $this->authorize('update', $kategori);

        $validated = $request->validated();

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);

        $kategori->update([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'parent_id'   => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
            'icon'        => $validated['icon'] ?? null,
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
        $this->authorize('delete', $kategori);

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
