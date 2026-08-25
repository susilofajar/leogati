<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Show the application landing storefront.
     */
    public function index(): View
    {
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->take(8)
            ->get();

        $brands = Brand::where('is_active', true)
            ->orderBy('name')
            ->take(12)
            ->get();

        $featuredProducts = Product::active()
            ->featured()
            ->with(['brand', 'category', 'variants', 'primaryImage'])
            ->take(8)
            ->get();

        return view('home', compact('categories', 'brands', 'featuredProducts'));
    }
}
