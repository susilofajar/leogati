<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class WarehouseSeeder extends Seeder
{
    public function run(): void
    {
        // Gudang pusat utama (default)
        $warehouse = Warehouse::firstOrCreate(
            ['code' => 'GUD-PUSAT'],
            [
                'name'        => 'Gudang Pusat LEOGATISTORE',
                'address'     => 'Jl. Soekarno-Hatta No. 88, Kendal',
                'city'        => 'Kendal',
                'province'    => 'Jawa Tengah',
                'postal_code' => '51311',
                'phone'       => '0813-2589-2020',
                'pic_name'    => 'Kepala Gudang Kendal',
                'is_active'   => true,
                'is_default'  => true,
            ]
        );

        // Inisialisasi inventory awal untuk semua varian yang sudah terdaftar
        $variants = ProductVariant::all();
        foreach ($variants as $variant) {
            Inventory::firstOrCreate(
                [
                    'product_variant_id' => $variant->id,
                    'warehouse_id'       => $warehouse->id,
                ],
                [
                    'quantity'          => $variant->stock,
                    'reserved_quantity' => 0,
                ]
            );
        }
    }
}
