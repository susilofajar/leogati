<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Services\CacheService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected CacheService $cacheService
    ) {}

    /**
     * Show the application landing storefront.
     */
    public function index(): View
    {
        $categories = $this->cacheService->getCachedCategories()
            ->whereNull('parent_id')
            ->take(8);

        $brands = $this->cacheService->getCachedBrands()
            ->take(12);

        $featuredProducts = $this->cacheService->getCachedFeaturedProducts(8);

        return view('home', compact('categories', 'brands', 'featuredProducts'));
    }
}
