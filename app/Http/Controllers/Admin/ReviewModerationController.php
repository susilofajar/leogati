<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewModerationController extends Controller
{
    public function __construct(
        protected ReviewService $reviewService
    ) {}

    /**
     * Tampilkan daftar seluruh ulasan produk untuk moderasi admin.
     */
    public function index(Request $request): View
    {
        $query = Review::with(['product', 'user', 'order'])->latest('id');

        if ($request->has('approved')) {
            $query->where('is_approved', $request->boolean('approved'));
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('comment', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.ulasan.index', compact('reviews'));
    }

    /**
     * Tampilkan rincian ulasan produk dan form tanggapan admin.
     */
    public function show(Review $ulasan): View
    {
        $review = $ulasan->load(['product.primaryImage', 'user', 'order']);

        return view('admin.ulasan.show', compact('review'));
    }

    /**
     * Setujui / sembunyikan status moderasi ulasan produk.
     */
    public function toggleApproval(Review $ulasan): RedirectResponse
    {
        $ulasan->update(['is_approved' => ! $ulasan->is_approved]);
        $statusText = $ulasan->is_approved ? 'disetujui dan ditampilkan ke publik' : 'disembunyikan dari publik';

        return redirect()->back()
            ->with('success', "Ulasan dari {$ulasan->user->name} berhasil {$statusText}.");
    }

    /**
     * Balas ulasan pelanggan sebagai tanggapan resmi toko.
     */
    public function reply(Request $request, Review $ulasan): RedirectResponse
    {
        $request->validate([
            'admin_reply' => ['required', 'string', 'min:5', 'max:2000'],
        ], [
            'admin_reply.required' => 'Isi tanggapan resmi penjual wajib ditulis.',
        ]);

        $this->reviewService->replyReview($ulasan, $request->input('admin_reply'));

        return redirect()->route('admin.ulasan.show', $ulasan->id)
            ->with('success', 'Tanggapan resmi toko LEOGATISTORE berhasil dikirimkan.');
    }
}
