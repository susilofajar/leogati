<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Tampilkan daftar merek resmi.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Brand::class);

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
        $this->authorize('create', Brand::class);

        return view('admin.merek.create');
    }

    /**
     * Simpan merek baru.
     */
    public function store(StoreBrandRequest $request)
    {
        $this->authorize('create', Brand::class);

        $validated = $request->validated();

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Brand::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            if (!$request->user() || !$request->user()->isSuperAdmin()) {
                return back()->withInput()->withErrors(['logo' => 'Hanya Super Admin yang berhak mengunggah logo resmi mitra.']);
            }
            $path = $request->file('logo')->store('brands', 'public');
            $logoPath = 'storage/' . $path;
        }

        $brand = Brand::create([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'logo'        => $logoPath,
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
        $this->authorize('update', $merek);

        return view('admin.merek.edit', ['brand' => $merek]);
    }

    /**
     * Perbarui merek.
     */
    public function update(UpdateBrandRequest $request, Brand $merek)
    {
        $this->authorize('update', $merek);

        $validated = $request->validated();

        $slug = $request->filled('slug') ? $request->slug : Str::slug($validated['name']);

        $logoPath = $merek->logo;
        if ($request->hasFile('logo')) {
            if (!$request->user() || !$request->user()->isSuperAdmin()) {
                return back()->withInput()->withErrors(['logo' => 'Hanya Super Admin yang berhak mengubah logo resmi mitra.']);
            }
            $path = $request->file('logo')->store('brands', 'public');
            $logoPath = 'storage/' . $path;
        }

        $merek->update([
            'name'        => $validated['name'],
            'slug'        => $slug,
            'logo'        => $logoPath,
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
        $this->authorize('delete', $merek);

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
