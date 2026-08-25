<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BrandController extends Controller
{
    /**
     * Tampilkan daftar merek resmi.
     */
    public function index(Request $request)
    {
        $query = Brand::withCount('products');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($merek) use ($q) {
                $merek->where('name', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $brands = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.merek.index', compact('brands'));
    }

    /**
     * Tampilkan form tambah merek.
     */
    public function create()
    {
        return view('admin.merek.create');
    }

    /**
     * Simpan merek baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:brands,slug',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Brand::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $brand = Brand::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->has('is_active'),
        ]);

        AuditLogService::log(
            'create_brand',
            'Brand',
            $brand->id,
            $brand->toArray()
        );

        return redirect()->route('admin.merek.index')
            ->with('success', "Merek '{$brand->name}' berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit merek.
     */
    public function edit(Brand $merek)
    {
        return view('admin.merek.edit', ['brand' => $merek]);
    }

    /**
     * Perbarui merek.
     */
    public function update(Request $request, Brand $merek)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('brands', 'slug')->ignore($merek->id)],
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);

        $merek->update([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'is_active'   => $request->has('is_active'),
        ]);

        AuditLogService::log(
            'update_brand',
            'Brand',
            $merek->id,
            $merek->toArray()
        );

        return redirect()->route('admin.merek.index')
            ->with('success', "Merek '{$merek->name}' berhasil diperbarui.");
    }

    /**
     * Hapus merek.
     */
    public function destroy(Brand $merek)
    {
        if ($merek->products()->count() > 0) {
            return back()->with('error', "Merek '{$merek->name}' tidak dapat dihapus karena masih memiliki {$merek->products()->count()} produk terkait.");
        }

        $id = $merek->id;
        $name = $merek->name;
        $merek->delete();

        AuditLogService::log(
            'delete_brand',
            'Brand',
            $id,
            ['name' => $name]
        );

        return redirect()->route('admin.merek.index')
            ->with('success', "Merek '{$name}' berhasil dihapus.");
    }
}
