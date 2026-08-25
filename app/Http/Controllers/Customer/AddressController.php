<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Tampilkan daftar alamat pelanggan.
     */
    public function index()
    {
        $addresses = Auth::user()
            ->addresses()
            ->orderByDesc('is_primary')
            ->orderByDesc('updated_at')
            ->get();

        return view('customer.addresses.index', compact('addresses'));
    }

    /**
     * Simpan alamat baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $user = Auth::user();

        // Jika belum punya alamat, otomatis jadikan primary
        $isPrimary = $user->addresses()->count() === 0;

        $user->addresses()->create(array_merge($validated, [
            'is_primary' => $isPrimary,
        ]));

        return back()->with('success', 'Alamat baru berhasil ditambahkan.');
    }

    /**
     * Update alamat yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $address->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Hapus alamat.
     */
    public function destroy($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($address->is_primary) {
            return back()->with('error', 'Alamat utama tidak dapat dihapus. Ubah alamat utama terlebih dahulu.');
        }

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    /**
     * Set alamat sebagai alamat utama.
     */
    public function setDefault($id)
    {
        $address = Address::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        DB::transaction(function () use ($address) {
            // Remove primary dari semua alamat user
            Address::where('user_id', $address->user_id)
                ->update(['is_primary' => false]);

            $address->update(['is_primary' => true]);
        });

        return back()->with('success', 'Alamat utama berhasil diperbarui.');
    }
}
