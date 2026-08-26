<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Akses penuh ke seluruh konfigurasi dan data sistem LEOGATISTORE.',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator Toko',
                'description' => 'Mengelola operasional katalog produk, pesanan, promosi, dan pelanggan.',
            ],
            [
                'name' => 'warehouse_staff',
                'display_name' => 'Staf Gudang & Logistik',
                'description' => 'Mengelola stok barang, mutasi gudang, pengemasan, dan nomor seri.',
            ],
            [
                'name' => 'sales_staff',
                'display_name' => 'Staf Penjualan',
                'description' => 'Mengelola pesanan penjualan, layanan pelanggan, dan verifikasi pesanan.',
            ],
            [
                'name' => 'finance_staff',
                'display_name' => 'Staf Keuangan',
                'description' => 'Mengelola verifikasi pembayaran, faktur/invoice, pengembalian dana, dan laporan keuangan.',
            ],
            [
                'name' => 'customer',
                'display_name' => 'Pelanggan',
                'description' => 'Pengguna toko yang berbelanja, merakit PC, dan memeriksa garansi produk.',
            ],
        ];

        $roleModels = [];
        foreach ($roles as $roleData) {
            $roleModels[$roleData['name']] = Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        // 2. Permissions
        $permissions = [
            ['name' => 'manage_users', 'display_name' => 'Kelola Pengguna', 'description' => 'Dapat menambah, mengubah, dan menghapus akun pengguna.'],
            ['name' => 'manage_roles', 'display_name' => 'Kelola Hak Akses & Peran', 'description' => 'Dapat mengatur hak akses dan peran sistem.'],
            ['name' => 'manage_products', 'display_name' => 'Kelola Produk & Varian', 'description' => 'Dapat mengelola katalog produk dan spesifikasi.'],
            ['name' => 'manage_categories', 'display_name' => 'Kelola Kategori', 'description' => 'Dapat mengelola struktur kategori produk.'],
            ['name' => 'manage_brands', 'display_name' => 'Kelola Merek', 'description' => 'Dapat mengelola data merek/manufaktur.'],
            ['name' => 'manage_inventory', 'display_name' => 'Kelola Inventaris & Stok', 'description' => 'Dapat mengelola stok gudang dan nomor seri.'],
            ['name' => 'manage_orders', 'display_name' => 'Kelola Pesanan', 'description' => 'Dapat melihat dan memproses pesanan pelanggan.'],
            ['name' => 'manage_suppliers', 'display_name' => 'Kelola Pemasok / Supplier', 'description' => 'Dapat mengelola data vendor dan pemasok barang.'],
            ['name' => 'manage_purchases', 'display_name' => 'Kelola Pembelian (PO)', 'description' => 'Dapat membuat PO dan penerimaan barang.'],
            ['name' => 'manage_warranties', 'display_name' => 'Kelola Garansi & Klaim', 'description' => 'Dapat memproses klaim dan status garansi produk.'],
            ['name' => 'manage_promotions', 'display_name' => 'Kelola Promo & Kupon', 'description' => 'Dapat membuat diskon dan kupon belanja.'],
            ['name' => 'manage_reviews', 'display_name' => 'Kelola & Moderasi Ulasan', 'description' => 'Dapat menyetujui, menyembunyikan, dan membalas ulasan produk.'],
            ['name' => 'view_reports', 'display_name' => 'Lihat Laporan & Analitik', 'description' => 'Dapat mengakses laporan penjualan dan keuangan.'],
            ['name' => 'manage_settings', 'display_name' => 'Kelola Pengaturan Sistem', 'description' => 'Dapat mengubah konfigurasi platform.'],
        ];

        $permissionModels = [];
        foreach ($permissions as $permData) {
            $permissionModels[$permData['name']] = Permission::updateOrCreate(
                ['name' => $permData['name']],
                $permData
            );
        }

        // 3. Assign permissions to roles
        // Super admin gets all permissions
        $roleModels['super_admin']->permissions()->sync(
            collect($permissionModels)->pluck('id')->toArray()
        );

        // Admin permissions
        $roleModels['admin']->permissions()->sync([
            $permissionModels['manage_products']->id,
            $permissionModels['manage_categories']->id,
            $permissionModels['manage_brands']->id,
            $permissionModels['manage_orders']->id,
            $permissionModels['manage_promotions']->id,
            $permissionModels['manage_reviews']->id,
            $permissionModels['manage_warranties']->id,
            $permissionModels['view_reports']->id,
        ]);

        // Warehouse staff permissions
        $roleModels['warehouse_staff']->permissions()->sync([
            $permissionModels['manage_inventory']->id,
            $permissionModels['manage_warranties']->id,
        ]);

        // Sales staff permissions
        $roleModels['sales_staff']->permissions()->sync([
            $permissionModels['manage_orders']->id,
            $permissionModels['manage_promotions']->id,
        ]);

        // Finance staff permissions
        $roleModels['finance_staff']->permissions()->sync([
            $permissionModels['manage_orders']->id,
            $permissionModels['view_reports']->id,
        ]);

        // 4. Default Seeded Users
        $defaultUsers = [
            [
                'name' => 'Super Administrator LEOGATI',
                'email' => 'superadmin@leogati.store',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ],
            [
                'name' => 'Admin Operasional',
                'email' => 'admin@leogati.store',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staf Logistik & Gudang',
                'email' => 'gudang@leogati.store',
                'password' => Hash::make('password'),
                'role' => 'warehouse_staff',
            ],
            [
                'name' => 'Staf Penjualan Toko',
                'email' => 'sales@leogati.store',
                'password' => Hash::make('password'),
                'role' => 'sales_staff',
            ],
            [
                'name' => 'Staf Keuangan & Pembayaran',
                'email' => 'finance@leogati.store',
                'password' => Hash::make('password'),
                'role' => 'finance_staff',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'pelanggan@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
        ];

        foreach ($defaultUsers as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']);

            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );

            $user->roles()->sync([$roleModels[$roleName]->id]);
        }
    }
}
