<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Categories
        $categories = [
            [
                'name' => 'Laptop',
                'description' => 'Laptop Gaming, Ultrabook, Bisnis, dan Pelajar.',
                'icon' => 'laptop',
                'sort_order' => 1,
            ],
            [
                'name' => 'Komputer Desktop & PC Rakitan',
                'description' => 'PC Rakitan Gaming, Workstation, dan All-in-One PC.',
                'icon' => 'desktop',
                'sort_order' => 2,
            ],
            [
                'name' => 'Komponen PC',
                'description' => 'Prosesor (CPU), Kartu Grafis (GPU), Motherboard, RAM, PSU, dan Casing.',
                'icon' => 'cpu',
                'sort_order' => 3,
            ],
            [
                'name' => 'Monitor',
                'description' => 'Monitor Gaming, 4K UHD, Curved, dan Monitor Desain Grafis.',
                'icon' => 'monitor',
                'sort_order' => 4,
            ],
            [
                'name' => 'Aksesoris Komputer',
                'description' => 'Keyboard Mekanikal, Mouse, Headset, Webcam, dan Speaker.',
                'icon' => 'keyboard',
                'sort_order' => 5,
            ],
            [
                'name' => 'Media Penyimpanan',
                'description' => 'SSD NVMe, SSD SATA, Harddisk Internal, dan HDD/SSD Eksternal.',
                'icon' => 'hard-drive',
                'sort_order' => 6,
            ],
            [
                'name' => 'Perangkat Jaringan',
                'description' => 'Router WiFi 6/7, Switch Hub, Access Point, dan Kabel LAN.',
                'icon' => 'wifi',
                'sort_order' => 7,
            ],
            [
                'name' => 'Printer & Pemindai',
                'description' => 'Printer Ink Tank, Laser, dan Tinta Asli.',
                'icon' => 'printer',
                'sort_order' => 8,
            ],
            [
                'name' => 'Gaming Gear',
                'description' => 'Kursi Gaming, Gamepad, Meja Gaming, dan Aksesoris Streaming.',
                'icon' => 'gamepad',
                'sort_order' => 9,
            ],
            [
                'name' => 'Perangkat Lunak & Digital',
                'description' => 'Sistem Operasi Windows, Microsoft Office, dan Antivirus Resmi.',
                'icon' => 'shield',
                'sort_order' => 10,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        // 2. Brands
        $brands = [
            ['name' => 'ASUS', 'description' => 'Produsen Laptop ROG, TUF Gaming, Motherboard, dan Komponen PC.'],
            ['name' => 'Lenovo', 'description' => 'Laptop Legion, ThinkPad, Yoga, dan Desktop Komputer.'],
            ['name' => 'HP', 'description' => 'Laptop Omen, Victus, Pavilion, dan Printer Ink Tank.'],
            ['name' => 'Acer', 'description' => 'Laptop Predator, Nitro, Swift, dan Monitor Gaming.'],
            ['name' => 'MSI', 'description' => 'Laptop Gaming, Kartu Grafis, Motherboard, dan Monitor.'],
            ['name' => 'Dell', 'description' => 'Laptop XPS, Alienware, Inspiron, dan Monitor Profesional.'],
            ['name' => 'Gigabyte', 'description' => 'Motherboard AORUS, Kartu Grafis, dan Komponen PC.'],
            ['name' => 'NVIDIA', 'description' => 'Kartu Grafis GeForce RTX Series dan Akselerator AI.'],
            ['name' => 'AMD', 'description' => 'Prosesor Ryzen dan Kartu Grafis Radeon RX Series.'],
            ['name' => 'Intel', 'description' => 'Prosesor Intel Core Series dan Kartu Grafis Arc.'],
            ['name' => 'Logitech', 'description' => 'Keyboard, Mouse Gaming G Series, Webcam, dan Headset.'],
            ['name' => 'Razer', 'description' => 'Periferal Gaming Premium, Headset, Mouse, dan Keyboard.'],
            ['name' => 'Samsung', 'description' => 'Monitor Gaming Odyssey dan SSD NVMe 990 Pro / EVO.'],
            ['name' => 'LG', 'description' => 'Monitor Gaming UltraGear, Monitor OLED, dan Aksesoris.'],
            ['name' => 'Corsair', 'description' => 'Power Supply (PSU), RAM Vengeance, Water Cooler, dan Casing PC.'],
            ['name' => 'Kingston', 'description' => 'RAM Kingston FURY dan SSD NVMe Berkecepatan Tinggi.'],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($brand['name'])],
                [
                    'name' => $brand['name'],
                    'slug' => Str::slug($brand['name']),
                    'description' => $brand['description'],
                    'is_active' => true,
                ]
            );
        }

        // 3. Default Promotional Coupons
        $coupons = [
            [
                'code' => 'LEOGATIBARU',
                'name' => 'Voucher Selamat Datang Pengguna Baru 10%',
                'type' => 'percent',
                'value' => 10,
                'min_purchase_amount' => 500000,
                'max_discount_amount' => 500000,
                'usage_limit' => 1000,
                'is_active' => true,
            ],
            [
                'code' => 'GAMING100K',
                'name' => 'Potongan Langsung Gaming & PC Rp 100.000',
                'type' => 'fixed',
                'value' => 100000,
                'min_purchase_amount' => 2000000,
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'DISKON50K',
                'name' => 'Diskon Aksesoris & Komponen Rp 50.000',
                'type' => 'fixed',
                'value' => 50000,
                'min_purchase_amount' => 300000,
                'usage_limit' => 500,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            \App\Models\Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }
    }
}
