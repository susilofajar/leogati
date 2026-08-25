<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\SavedPcBuild;
use App\Services\CartService;
use App\Services\PcBuilderCompatibilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PcBuilderController extends Controller
{
    public function __construct(
        protected PcBuilderCompatibilityService $compatibilityService,
        protected CartService $cartService
    ) {}

    /**
     * Tampilkan antarmuka simulator PC Builder.
     */
    public function index(Request $request): View
    {
        // Ambil semua komponen PC berdasarkan kategori spesifikasinya
        $allComponents = ProductVariant::with(['product.brand', 'product.category', 'product.specifications.attribute'])
            ->where('is_active', true)
            ->whereHas('product', fn($q) => $q->where('is_active', true))
            ->get();

        // Kelompokkan komponen ke dalam slot PC Builder
        $categorizedComponents = [
            'cpu'         => $allComponents->filter(fn($v) => $this->hasSpec($v, 'cpu_socket')),
            'motherboard' => $allComponents->filter(fn($v) => $this->hasSpec($v, 'mb_socket')),
            'ram'         => $allComponents->filter(fn($v) => $this->hasSpec($v, 'ram_type')),
            'gpu'         => $allComponents->filter(fn($v) => $this->hasSpec($v, 'gpu_chipset')),
            'storage'     => $allComponents->filter(fn($v) => $this->hasSpec($v, 'storage_interface')),
            'psu'         => $allComponents->filter(fn($v) => $this->hasSpec($v, 'psu_wattage')),
            'casing'      => $allComponents->filter(fn($v) => $this->hasSpec($v, 'case_form_factor') || str_contains(strtolower($v->product->name), 'casing') || str_contains(strtolower($v->product->name), 'case')),
            'cooler'      => $allComponents->filter(fn($v) => $this->hasSpec($v, 'cooler_socket') || str_contains(strtolower($v->product->name), 'cooler')),
        ];

        // Jika ada share token yang dibuka
        $savedBuild = null;
        if ($token = $request->query('share')) {
            $savedBuild = SavedPcBuild::where('share_token', $token)->first();
        }

        return view('pc-builder.index', compact('categorizedComponents', 'savedBuild'));
    }

    /**
     * Validasi live kompatibilitas & daya (Endpoint JSON / Livewire / Alpine).
     */
    public function validateBuild(Request $request): JsonResponse
    {
        $selected = $request->input('components', []);
        $evaluation = $this->compatibilityService->evaluateBuild($selected);

        return response()->json([
            'status'            => $evaluation['status'],
            'status_label'      => $evaluation['status_label'],
            'status_color'      => $evaluation['status_color'],
            'messages'          => $evaluation['messages'],
            'estimated_wattage' => $evaluation['estimated_wattage'],
            'recommended_psu'   => $evaluation['recommended_psu'],
            'total_price'       => $evaluation['total_price'],
            'total_price_idr'   => rupiah($evaluation['total_price']),
        ]);
    }

    /**
     * Simpan rakitan PC untuk dibagikan atau disimpan ke profil.
     */
    public function saveBuild(Request $request): JsonResponse
    {
        $request->validate([
            'build_name' => 'nullable|string|max:100',
            'components' => 'required|array',
        ]);

        $components = array_filter($request->input('components'));
        $evaluation = $this->compatibilityService->evaluateBuild($components);

        $savedBuild = SavedPcBuild::create([
            'share_token'            => SavedPcBuild::generateToken(),
            'user_id'                => $request->user()?->id,
            'build_name'             => $request->input('build_name') ?: 'Simulasi PC ' . date('d/m/Y H:i'),
            'components'             => $components,
            'total_price'            => $evaluation['total_price'],
            'estimated_wattage'      => $evaluation['estimated_wattage'],
            'compatibility_status'   => $evaluation['status'],
            'compatibility_messages' => $evaluation['messages'],
            'notes'                  => $request->input('notes'),
        ]);

        return response()->json([
            'success'     => true,
            'share_token' => $savedBuild->share_token,
            'share_url'   => route('pc_builder.index', ['share' => $savedBuild->share_token]),
            'message'     => 'Rakitan PC berhasil disimpan. Anda dapat membagikan tautan ini.',
        ]);
    }

    /**
     * Masukkan semua komponen yang dipilih ke dalam Keranjang Belanja sekaligus.
     */
    public function addBuildToCart(Request $request)
    {
        $components = array_filter($request->input('components', []));

        if (empty($components)) {
            return back()->withErrors(['components' => 'Pilih minimal satu komponen PC terlebih dahulu.']);
        }

        $evaluation = $this->compatibilityService->evaluateBuild($components);

        if ($evaluation['status'] === 'incompatible' && ! $request->boolean('force_incompatible')) {
            return back()->withErrors([
                'compatibility' => 'Rakitan PC ini memiliki komponen yang tidak kompatibel. Periksa kembali pilihan Anda sebelum memesan.',
            ]);
        }

        $addedCount = 0;
        foreach ($components as $slot => $variantId) {
            $variant = ProductVariant::find($variantId);
            if ($variant && $variant->stock > 0) {
                $this->cartService->addItem((int) $variant->id, 1);
                $addedCount++;
            }
        }

        return redirect()->route('cart.index')->with('success', "Berhasil menambahkan {$addedCount} komponen PC ke keranjang belanja Anda!");
    }

    /**
     * Helper cek keberadaan spesifikasi pada produk.
     */
    protected function hasSpec(ProductVariant $variant, string $attributeSlug): bool
    {
        return $variant->product->specifications->contains(function ($s) use ($attributeSlug) {
            return $s->attribute && $s->attribute->slug === $attributeSlug;
        });
    }
}
