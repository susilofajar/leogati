<?php

use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PurchaseOrderController as AdminPurchaseOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ReviewModerationController as AdminReviewModerationController;
use App\Http\Controllers\Admin\SerialNumberController as AdminSerialNumberController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\WarehouseController as AdminWarehouseController;
use App\Http\Controllers\Admin\WarrantyClaimController as AdminWarrantyClaimController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\Customer\AddressController as CustomerAddressController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\NotificationController as CustomerNotificationController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\SavedBuildController as CustomerSavedBuildController;
use App\Http\Controllers\Customer\WishlistController as CustomerWishlistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\PcBuilderController;
use App\Http\Controllers\ProductCatalogController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WarrantyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rute SEO & Mesin Pencari
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

/*
|--------------------------------------------------------------------------
| Rute Publik / Storefront (Katalog, Garansi & Keranjang)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Katalog Produk Storefront
Route::get('/produk', [ProductCatalogController::class, 'index'])->name('products.index');
Route::get('/produk/{slug}', [ProductCatalogController::class, 'show'])->name('products.show');
Route::get('/kategori/{slug}', [ProductCatalogController::class, 'byCategory'])->name('categories.show');
Route::get('/merek/{slug}', [ProductCatalogController::class, 'byBrand'])->name('brands.show');

// Perbandingan Produk (Product Comparison)
Route::get('/bandingkan', [ComparisonController::class, 'index'])->name('comparison.index');
Route::post('/bandingkan/tambah', [ComparisonController::class, 'add'])->name('comparison.add');
Route::delete('/bandingkan/hapus/{id}', [ComparisonController::class, 'remove'])->name('comparison.remove');

// Garansi & PC Builder
Route::get('/garansi/cek', [WarrantyController::class, 'check'])->name('warranty.check');
Route::get('/pc-builder', [PcBuilderController::class, 'index'])->name('pc_builder.index');
Route::post('/pc-builder/validasi', [PcBuilderController::class, 'validateBuild'])->name('pc_builder.validate');
Route::post('/pc-builder/simpan', [PcBuilderController::class, 'saveBuild'])->name('pc_builder.save');
Route::post('/pc-builder/tambah-keranjang', [PcBuilderController::class, 'addBuildToCart'])->name('pc_builder.add_to_cart');

// Keranjang Belanja & Kupon Promo
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
Route::put('/keranjang/ubah/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang/kosongkan', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/keranjang/kupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');
Route::delete('/keranjang/kupon', [CartController::class, 'removeCoupon'])->name('cart.remove_coupon');

/*
|--------------------------------------------------------------------------
| Rute Autentikasi Pengguna (Bahasa Indonesia)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login']);

    Route::get('/daftar', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register']);
});

Route::post('/keluar', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Pelanggan (Terotentikasi: Checkout, Pesanan, Garansi, Wishlist & Profil)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Alur Kasir / Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Wishlist Toggle
    Route::post('/wishlist/toggle', [CustomerWishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{id}', [CustomerWishlistController::class, 'destroy'])->name('wishlist.destroy');

    // Pengajuan Klaim Garansi
    Route::get('/garansi/klaim', [WarrantyController::class, 'claimForm'])->name('warranty.claim_form');
    Route::post('/garansi/klaim', [WarrantyController::class, 'submitClaim'])->name('warranty.submit_claim');

    // Ulasan Produk Terverifikasi
    Route::post('/produk/{slug}/ulasan', [ProductReviewController::class, 'store'])->name('products.reviews.store');

    // Dashboard & Riwayat Pelanggan
    Route::prefix('akun')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        
        // Pesanan
        Route::get('/pesanan', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan/{order_number}', [CustomerOrderController::class, 'show'])->name('orders.show');
        
        // Garansi
        Route::get('/garansi', [WarrantyController::class, 'myClaims'])->name('warranty.index');
        Route::get('/garansi/{claim_number}', [WarrantyController::class, 'showClaim'])->name('warranty.show');
        
        // Notifikasi
        Route::get('/notifikasi', [CustomerNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifikasi/{id}/baca', [CustomerNotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifikasi/baca-semua', [CustomerNotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

        // Wishlist
        Route::get('/wishlist', [CustomerWishlistController::class, 'index'])->name('wishlist.index');

        // Profil & Keamanan
        Route::get('/profil', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profil', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profil/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');

        // Buku Alamat
        Route::get('/alamat', [CustomerAddressController::class, 'index'])->name('addresses.index');
        Route::post('/alamat', [CustomerAddressController::class, 'store'])->name('addresses.store');
        Route::put('/alamat/{id}', [CustomerAddressController::class, 'update'])->name('addresses.update');
        Route::delete('/alamat/{id}', [CustomerAddressController::class, 'destroy'])->name('addresses.destroy');
        Route::post('/alamat/{id}/utama', [CustomerAddressController::class, 'setDefault'])->name('addresses.set_default');

        // Racikan PC Tersimpan
        Route::get('/racikan', [CustomerSavedBuildController::class, 'index'])->name('builds.index');
        Route::get('/racikan/{token}', [CustomerSavedBuildController::class, 'show'])->name('builds.show');
        Route::delete('/racikan/{token}', [CustomerSavedBuildController::class, 'destroy'])->name('builds.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Payment Gateway Webhook Routes
|--------------------------------------------------------------------------
*/
Route::post('/webhook/midtrans', [PaymentWebhookController::class, 'handleMidtransWebhook'])
    ->name('webhook.midtrans');
Route::get('/webhook/midtrans', function () {
    return response()->json([
        'status'  => 'active',
        'service' => 'LEOGATISTORE Midtrans Payment Webhook',
        'message' => 'Endpoint ini hanya menerima notifikasi POST otomatis dari server Midtrans.',
    ]);
});

/*
|--------------------------------------------------------------------------
| Shipping API Routes
|--------------------------------------------------------------------------
*/
Route::get('/api/shipping/couriers', [ShippingController::class, 'getCouriers'])
    ->name('shipping.couriers');
Route::get('/api/shipping/services/{courier}', [ShippingController::class, 'getServices'])
    ->name('shipping.services');
Route::post('/api/shipping/calculate', [ShippingController::class, 'calculateCost'])
    ->name('shipping.calculate');
Route::post('/api/shipping/track', [ShippingController::class, 'track'])
    ->name('shipping.track');

/*
|--------------------------------------------------------------------------
| Rute Admin & Staf Operasional
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin,admin,warehouse_staff,sales_staff,finance_staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Katalog Produk, Kategori & Merek
        Route::resource('produk', AdminProductController::class);
        Route::resource('kategori', AdminCategoryController::class)->except(['show']);
        Route::resource('merek', AdminBrandController::class)->except(['show']);

        // Manajemen Akun Pengguna & Staf
        Route::resource('pengguna', AdminUserController::class)->except(['show']);

        // Manajemen Pesanan
        Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/{id}', [AdminOrderController::class, 'show'])->name('pesanan.show');
        Route::put('/pesanan/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('pesanan.update_status');

        // Gudang
        Route::get('/gudang', [AdminWarehouseController::class, 'index'])->name('gudang.index');
        Route::get('/gudang/{gudang}', [AdminWarehouseController::class, 'show'])->name('gudang.show');

        // Inventaris & Mutasi Stok
        Route::get('/inventaris', [AdminInventoryController::class, 'index'])->name('inventaris.index');
        Route::get('/inventaris/{varian}/mutasi', [AdminInventoryController::class, 'movements'])->name('inventaris.mutasi');
        Route::get('/inventaris/{varian}/sesuaikan', [AdminInventoryController::class, 'adjustForm'])->name('inventaris.adjust_form');
        Route::post('/inventaris/{varian}/sesuaikan', [AdminInventoryController::class, 'adjust'])->name('inventaris.adjust');

        // Nomor Seri
        Route::get('/nomor-seri', [AdminSerialNumberController::class, 'index'])->name('nomor_seri.index');
        Route::get('/nomor-seri/{nomor_seri}', [AdminSerialNumberController::class, 'show'])->name('nomor_seri.show');

        // Klaim Garansi
        Route::get('/garansi', [AdminWarrantyClaimController::class, 'index'])->name('garansi.index');
        Route::get('/garansi/{garansi}', [AdminWarrantyClaimController::class, 'show'])->name('garansi.show');
        Route::put('/garansi/{garansi}/status', [AdminWarrantyClaimController::class, 'updateStatus'])->name('garansi.update_status');

        // Kupon Promosi
        Route::resource('kupon', AdminCouponController::class)->except(['show'])->names([
            'index'   => 'kupon.index',
            'create'  => 'kupon.create',
            'store'   => 'kupon.store',
            'edit'    => 'kupon.edit',
            'update'  => 'kupon.update',
            'destroy' => 'kupon.destroy',
        ]);

        // Moderasi Ulasan Produk
        Route::get('/ulasan', [AdminReviewModerationController::class, 'index'])->name('ulasan.index');
        Route::get('/ulasan/{ulasan}', [AdminReviewModerationController::class, 'show'])->name('ulasan.show');
        Route::post('/ulasan/{ulasan}/toggle', [AdminReviewModerationController::class, 'toggleApproval'])->name('ulasan.toggle');
        Route::post('/ulasan/{ulasan}/balas', [AdminReviewModerationController::class, 'reply'])->name('ulasan.reply');

        // Supplier
        Route::resource('supplier', AdminSupplierController::class)->names([
            'index'   => 'supplier.index',
            'create'  => 'supplier.create',
            'store'   => 'supplier.store',
            'show'    => 'supplier.show',
            'edit'    => 'supplier.edit',
            'update'  => 'supplier.update',
            'destroy' => 'supplier.destroy',
        ]);

        // Purchase Order (Pembelian)
        Route::get('/pembelian', [AdminPurchaseOrderController::class, 'index'])->name('pembelian.index');
        Route::get('/pembelian/buat', [AdminPurchaseOrderController::class, 'create'])->name('pembelian.create');
        Route::post('/pembelian', [AdminPurchaseOrderController::class, 'store'])->name('pembelian.store');
        Route::get('/pembelian/{pembelian}', [AdminPurchaseOrderController::class, 'show'])->name('pembelian.show');
        Route::post('/pembelian/{pembelian}/kirim', [AdminPurchaseOrderController::class, 'markSent'])->name('pembelian.kirim');
        Route::post('/pembelian/{pembelian}/terima', [AdminPurchaseOrderController::class, 'receiveGoods'])->name('pembelian.terima');
        Route::post('/pembelian/{pembelian}/batalkan', [AdminPurchaseOrderController::class, 'cancel'])->name('pembelian.batalkan');

        // Laporan & Analitik
        Route::get('/laporan/penjualan', [AdminReportController::class, 'penjualan'])->name('laporan.penjualan');
        Route::get('/laporan/inventaris', [AdminReportController::class, 'inventaris'])->name('laporan.inventaris');
        Route::get('/laporan/pembelian', [AdminReportController::class, 'pembelian'])->name('laporan.pembelian');
        Route::get('/laporan/pelanggan', [AdminReportController::class, 'pelanggan'])->name('laporan.pelanggan');

        // Audit Log Keamanan
        Route::get('/audit-log', [AdminAuditLogController::class, 'index'])->name('audit_log.index');
    });

