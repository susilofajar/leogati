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
                'address'     => 'Jl. Teknologi Raya No. 1',
                'city'        => 'Jakarta Selatan',
                'province'    => 'DKI Jakarta',
                'postal_code' => '12560',
                'phone'       => '021-55500001',
                'pic_name'    => 'Kepala Gudang',
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
