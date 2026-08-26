<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\SpecificationGroup;
use App\Services\CacheService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected CacheService $cacheService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Product::class);

        $query = Product::with(['category', 'brand', 'variants', 'primaryImage']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%");
                  });
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Product::class);

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $specGroups = SpecificationGroup::with('attributes')->orderBy('sort_order')->get();

        return view('admin.products.create', compact('categories', 'brands', 'specGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->authorize('create', Product::class);

        DB::transaction(function () use ($request) {
            $slug = Str::slug($request->name);
            // Ensure unique slug
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}-{$count}";
                $count++;
            }

            $product = Product::create([
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'slug' => $slug,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'warranty_period_months' => $request->warranty_period_months,
                'status' => $request->status,
                'is_featured' => $request->boolean('is_featured'),
            ]);

            // Create Primary Variant
            ProductVariant::create([
                'product_id' => $product->id,
                'name' => 'Standar / Default',
                'sku' => strtoupper(trim($request->sku)),
                'price' => $request->price,
                'cost_price' => $request->cost_price ?? 0,
                'stock' => $request->stock,
                'weight_grams' => $request->weight_grams,
                'is_default' => true,
                'is_active' => true,
            ]);

            // Handle Uploaded Images
            if ($request->hasFile('images')) {
                $primaryIndex = (int) $request->input('primary_image_index', 0);
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'storage/' . $path,
                        'is_primary' => ($index === $primaryIndex),
                        'sort_order' => $index,
                    ]);
                }
            }
        });

        $this->cacheService->flushCatalogCache();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk baru berhasil ditambahkan ke katalog!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $produk): View
    {
        $this->authorize('update', $produk);

        $product = $produk->load(['category', 'brand', 'variants', 'specifications', 'images']);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $defaultVariant = $product->variants()->where('is_default', true)->first();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'defaultVariant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $produk): RedirectResponse
    {
        $this->authorize('update', $produk);

        DB::transaction(function () use ($request, $produk) {
            $produk->update([
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'warranty_period_months' => $request->warranty_period_months,
                'status' => $request->status,
                'is_featured' => $request->boolean('is_featured'),
            ]);

            $defaultVariant = $produk->variants()->where('is_default', true)->first();
            if ($defaultVariant) {
                $defaultVariant->update([
                    'sku' => strtoupper(trim($request->sku)),
                    'price' => $request->price,
                    'cost_price' => $request->cost_price ?? 0,
                    'stock' => $request->stock,
                    'weight_grams' => $request->weight_grams,
                ]);
            }

            // 1. Handle Deleted Images
            if ($request->filled('delete_images')) {
                $imagesToDelete = ProductImage::where('product_id', $produk->id)
                    ->whereIn('id', $request->delete_images)
                    ->get();

                foreach ($imagesToDelete as $img) {
                    $relative = str_replace('storage/', '', $img->image_path);
                    if (Storage::disk('public')->exists($relative)) {
                        Storage::disk('public')->delete($relative);
                    }
                    $img->delete();
                }
            }

            // 2. Handle Primary Image Selection among existing
            if ($request->filled('primary_image_id')) {
                ProductImage::where('product_id', $produk->id)->update(['is_primary' => false]);
                ProductImage::where('product_id', $produk->id)
                    ->where('id', $request->primary_image_id)
                    ->update(['is_primary' => true]);
            }

            // 3. Handle Newly Uploaded Images
            if ($request->hasFile('images')) {
                $currentCount = $produk->images()->count();
                $hasExistingPrimary = $produk->images()->where('is_primary', true)->exists();

                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    $isPrimary = (!$hasExistingPrimary && $index === 0);

                    ProductImage::create([
                        'product_id' => $produk->id,
                        'image_path' => 'storage/' . $path,
                        'is_primary' => $isPrimary,
                        'sort_order' => $currentCount + $index,
                    ]);
                }
            }

            // Ensure at least one primary image exists if images are present
            if ($produk->images()->exists() && !$produk->images()->where('is_primary', true)->exists()) {
                $first = $produk->images()->first();
                if ($first) {
                    $first->update(['is_primary' => true]);
                }
            }
        });

        $this->cacheService->flushCatalogCache();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Data produk ' . $produk->name . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $produk): RedirectResponse
    {
        $this->authorize('delete', $produk);

        $name = $produk->name;
        $produk->delete();

        $this->cacheService->flushCatalogCache();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk ' . $name . ' berhasil dihapus dari katalog.');
    }
}
