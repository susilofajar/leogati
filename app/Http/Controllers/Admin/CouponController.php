<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CouponController extends Controller
{
    /**
     * Tampilkan daftar kupon promosi.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Coupon::class);

        $query = Coupon::latest('id');

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $coupons = $query->paginate(15)->withQueryString();

        return view('admin.kupon.index', compact('coupons'));
    }

    /**
     * Tampilkan formulir pembuatan kupon promo baru.
     */
    public function create(): View
    {
        $this->authorize('create', Coupon::class);

        return view('admin.kupon.create');
    }

    /**
     * Simpan kupon promo baru.
     */
    public function store(StoreCouponRequest $request): RedirectResponse
    {
        $this->authorize('create', Coupon::class);

        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        $coupon = Coupon::create($data);

        return redirect()->route('admin.kupon.index')
            ->with('success', "Kupon promo '{$coupon->code}' berhasil dibuat.");
    }

    /**
     * Tampilkan formulir edit kupon promo.
     */
    public function edit(Coupon $kupon): View
    {
        $this->authorize('update', $kupon);

        return view('admin.kupon.edit', compact('kupon'));
    }

    /**
     * Perbarui data kupon promo.
     */
    public function update(StoreCouponRequest $request, Coupon $kupon): RedirectResponse
    {
        $this->authorize('update', $kupon);

        $data = $request->validated();
        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active', true);

        $kupon->update($data);

        return redirect()->route('admin.kupon.index')
            ->with('success', "Kupon promo '{$kupon->code}' berhasil diperbarui.");
    }

    /**
     * Hapus kupon promo.
     */
    public function destroy(Coupon $kupon): RedirectResponse
    {
        $this->authorize('delete', $kupon);

        $code = $kupon->code;
        $kupon->delete();

        return redirect()->route('admin.kupon.index')
            ->with('success', "Kupon promo '{$code}' berhasil dihapus.");
    }
}
