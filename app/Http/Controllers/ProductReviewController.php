<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;

class ProductReviewController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Simpan ulasan produk dari pembeli terverifikasi.
     */
    public function store(StoreReviewRequest $request, string $slug): RedirectResponse
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $this->reviewService->createReview(
            auth()->user(),
            $product,
            $request->validated()
        );

        return redirect()->route('products.show', $product->slug)
            ->with('success', 'Terima kasih! Ulasan dan penilaian bintang Anda untuk ' . $product->name . ' berhasil dikirimkan.');
    }
}
