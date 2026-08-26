<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCatalogController extends Controller
{
    /**
     * Display a listing of products with filters and search.
     */
    public function index(Request $request): View
    {
        $query = Product::active()
            ->with(['category', 'brand', 'variants', 'primaryImage']);

        // Search by keyword
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%");
                  })
                  ->orWhereHas('brand', function ($bq) use ($search) {
                      $bq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Category
        if ($categorySlug = $request->input('kategori')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // Filter by Brand
        if ($brandSlug = $request->input('merek')) {
            $query->whereHas('brand', function ($q) use ($brandSlug) {
                $q->where('slug', $brandSlug);
            });
        }

        // Filter by Price Range
        if ($minPrice = $request->input('harga_min')) {
            $query->whereHas('variants', function ($q) use ($minPrice) {
                $q->where('price', '>=', (float) $minPrice);
            });
        }

        if ($maxPrice = $request->input('harga_max')) {
            $query->whereHas('variants', function ($q) use ($maxPrice) {
                $q->where('price', '<=', (float) $maxPrice);
            });
        }

        // Filter by Stock
        if ($request->boolean('stok_tersedia')) {
            $query->whereHas('variants', function ($q) {
                $q->where('stock', '>', 0);
            });
        }

        // Sorting
        $sort = $request->input('urutan', 'terbaru');
        switch ($sort) {
            case 'termurah':
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                      ->where('product_variants.is_default', true)
                      ->orderBy('product_variants.price', 'asc')
                      ->select('products.*');
                break;
            case 'termahal':
                $query->join('product_variants', 'products.id', '=', 'product_variants.product_id')
                      ->where('product_variants.is_default', true)
                      ->orderBy('product_variants.price', 'desc')
                      ->select('products.*');
                break;
            case 'nama_az':
                $query->orderBy('name', 'asc');
                break;
            case 'terbaru':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = $this->cacheService->getCachedCategories()->whereNull('parent_id');
        $brands = $this->cacheService->getCachedBrands();

        return view('products.index', compact('products', 'categories', 'brands'));
    }

    public function __construct(
        protected \App\Services\ReviewService $reviewService,
        protected \App\Services\CacheService $cacheService
    ) {}

    /**
     * Display product detail with structured specs, approved reviews, and review form eligibility.
     */
    public function show(string $slug): View
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with([
                'category',
                'brand',
                'variants' => function ($q) {
                    $q->where('is_active', true);
                },
                'images',
                'specifications.attribute.group',
                'reviews' => function ($q) {
                    $q->with('user')->latest('id');
                },
            ])
            ->firstOrFail();

        // Group specifications by Specification Group
        $groupedSpecs = $product->specifications
            ->groupBy(function ($spec) {
                return $spec->attribute->group->name ?? 'Lainnya';
            });

        // Related Products
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['brand', 'primaryImage', 'variants'])
            ->take(4)
            ->get();

        // Check if logged-in user is eligible to write a review
        $reviewEligibility = auth()->check() 
            ? $this->reviewService->checkUserEligibility(auth()->user(), $product)
            : ['can_review' => false, 'message' => 'Silakan masuk ke akun Anda untuk memberikan ulasan.'];

        return view('products.show', compact('product', 'groupedSpecs', 'relatedProducts', 'reviewEligibility'));
    }

    /**
     * Filter catalog by category slug.
     */
    public function byCategory(string $slug)
    {
        return redirect()->route('products.index', ['kategori' => $slug]);
    }

    /**
     * Filter catalog by brand slug.
     */
    public function byBrand(string $slug)
    {
        return redirect()->route('products.index', ['merek' => $slug]);
    }
}
