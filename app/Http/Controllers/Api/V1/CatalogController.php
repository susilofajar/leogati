<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CatalogController extends Controller
{
    /**
     * Ambil daftar katalog produk dengan filter dan paginasi.
     */
    public function products(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'brand', 'variants'])
            ->where('status', 'active');

        // Pencarian kata kunci
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('variants', fn($v) => $v->where('sku', 'like', "%{$search}%"));
            });
        }

        // Filter Kategori
        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', fn($c) => $c->where('slug', $categorySlug));
        }

        // Filter Merek
        if ($brandSlug = $request->query('brand')) {
            $query->whereHas('brand', fn($b) => $b->where('slug', $brandSlug));
        }

        // Pengurutan
        $sort = $request->query('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderByDesc('average_rating')->orderByDesc('reviews_count');
        } else {
            $query->latest();
        }

        $perPage = min(50, max(1, (int) $request->query('per_page', 15)));
        $products = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => ProductResource::collection($products)->response()->getData(true),
        ]);
    }

    /**
     * Ambil detail rincian produk tunggal berdasarkan slug.
     */
    public function productShow(string $slug): JsonResponse
    {
        $product = Product::with(['category', 'brand', 'variants', 'specifications.attribute.group', 'reviews.user'])
            ->where('status', 'active')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Ambil daftar kategori produk (dengan cache 60 menit).
     */
    public function categories(): JsonResponse
    {
        $categories = Cache::remember('api_categories_list', 3600, function () {
            return Category::where('is_active', true)
                ->withCount('products')
                ->orderBy('sort_order')
                ->get();
        });

        return response()->json([
            'status' => 'success',
            'data' => CategoryResource::collection($categories),
        ]);
    }

    /**
     * Ambil daftar merek produk resmi (dengan cache 60 menit).
     */
    public function brands(): JsonResponse
    {
        $brands = Cache::remember('api_brands_list', 3600, function () {
            return Brand::where('is_active', true)
                ->withCount('products')
                ->orderBy('name')
                ->get();
        });

        return response()->json([
            'status' => 'success',
            'data' => BrandResource::collection($brands),
        ]);
    }
}
