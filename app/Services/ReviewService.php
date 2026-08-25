<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    /**
     * Cek apakah pelanggan berhak mengulas produk ini (Verified Purchaser).
     *
     * @return array [can_review => bool, message => string, order_id => ?int]
     */
    public function checkUserEligibility(User $user, Product $product): array
    {
        // Cari pesanan pelanggan yang memuat varian produk ini dengan status valid (paid, processing, packed, shipped, delivered, completed)
        $validStatuses = ['paid', 'processing', 'packed', 'shipped', 'delivered', 'completed'];

        $orderItem = OrderItem::whereHas('order', function ($q) use ($user, $validStatuses) {
            $q->where('user_id', $user->id)
              ->whereIn('status', $validStatuses);
        })->whereHas('variant', function ($q) use ($product) {
            $q->where('product_id', $product->id);
        })->latest('id')->first();

        if (! $orderItem) {
            return [
                'can_review' => false,
                'message'    => 'Hanya pelanggan yang telah membeli produk ini yang dapat memberikan ulasan (Verified Purchase).',
                'order_id'   => null,
            ];
        }

        // Cek apakah sudah pernah mengulas produk ini
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->where('order_id', $orderItem->order_id)
            ->first();

        if ($existingReview) {
            return [
                'can_review' => false,
                'message'    => 'Anda sudah memberikan ulasan untuk produk ini pada pesanan #' . ($orderItem->order->order_number ?? '') . '.',
                'order_id'   => $orderItem->order_id,
            ];
        }

        return [
            'can_review' => true,
            'message'    => 'Anda memenuhi syarat untuk memberikan ulasan produk ini.',
            'order_id'   => $orderItem->order_id,
        ];
    }

    /**
     * Simpan ulasan baru dari pembeli terverifikasi.
     *
     * @throws ValidationException
     */
    public function createReview(User $user, Product $product, array $data): Review
    {
        $eligibility = $this->checkUserEligibility($user, $product);

        if (! $eligibility['can_review']) {
            throw ValidationException::withMessages([
                'rating' => $eligibility['message'],
            ]);
        }

        return Review::create([
            'product_id'           => $product->id,
            'user_id'              => $user->id,
            'order_id'             => $eligibility['order_id'],
            'rating'               => (int) $data['rating'],
            'title'                => $data['title'] ?? null,
            'comment'              => $data['comment'],
            'is_verified_purchase' => true,
            'is_approved'          => true, // Default disetujui langsung, dapat dimoderasi admin
        ]);
    }

    /**
     * Tanggapan resmi admin terhadap ulasan pembeli.
     */
    public function replyReview(Review $review, string $reply): Review
    {
        $review->update([
            'admin_reply'      => $reply,
            'admin_replied_at' => Carbon::now(),
        ]);

        return $review->fresh();
    }
}
