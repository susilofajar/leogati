<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSpecification;
use App\Models\ProductVariant;
use App\Models\SpecificationAttribute;
use App\Models\SpecificationGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. SPECIFICATION GROUPS & ATTRIBUTES
        $groupsData = [
            [
                'name' => 'Prosesor (CPU)',
                'slug' => 'processor',
                'sort_order' => 1,
                'attributes' => [
                    ['name' => 'Soket CPU', 'slug' => 'cpu_socket', 'is_filterable' => true],
                    ['name' => 'Jumlah Core', 'slug' => 'cpu_cores', 'unit' => 'Cores', 'is_filterable' => true],
                    ['name' => 'Jumlah Thread', 'slug' => 'cpu_threads', 'unit' => 'Threads'],
                    ['name' => 'Kecepatan Dasar', 'slug' => 'cpu_base_clock', 'unit' => 'GHz'],
                    ['name' => 'Kecepatan Boost', 'slug' => 'cpu_boost_clock', 'unit' => 'GHz'],
                    ['name' => 'Konsumsi Daya (TDP)', 'slug' => 'cpu_tdp', 'unit' => 'Watt', 'is_filterable' => true],
                ],
            ],
            [
                'name' => 'Memori (RAM)',
                'slug' => 'memory',
                'sort_order' => 2,
                'attributes' => [
                    ['name' => 'Tipe Memori', 'slug' => 'ram_type', 'is_filterable' => true],
                    ['name' => 'Kapasitas Memori', 'slug' => 'ram_capacity', 'unit' => 'GB', 'is_filterable' => true],
                    ['name' => 'Kecepatan Clock', 'slug' => 'ram_speed', 'unit' => 'MHz', 'is_filterable' => true],
                    ['name' => 'Konfigurasi Channel', 'slug' => 'ram_channel'],
                ],
            ],
            [
                'name' => 'Media Penyimpanan',
                'slug' => 'storage',
                'sort_order' => 3,
                'attributes' => [
                    ['name' => 'Tipe Antarmuka', 'slug' => 'storage_interface', 'is_filterable' => true],
                    ['name' => 'Form Factor Penyimpanan', 'slug' => 'storage_form_factor'],
                    ['name' => 'Kapasitas Penyimpanan', 'slug' => 'storage_capacity', 'unit' => 'GB', 'is_filterable' => true],
                    ['name' => 'Kecepatan Baca (Read)', 'slug' => 'storage_read_speed', 'unit' => 'MB/s'],
                    ['name' => 'Kecepatan Tulis (Write)', 'slug' => 'storage_write_speed', 'unit' => 'MB/s'],
                ],
            ],
            [
                'name' => 'Kartu Grafis (GPU)',
                'slug' => 'graphics',
                'sort_order' => 4,
                'attributes' => [
                    ['name' => 'Chipset GPU', 'slug' => 'gpu_chipset', 'is_filterable' => true],
                    ['name' => 'Kapasitas VRAM', 'slug' => 'gpu_vram', 'unit' => 'GB', 'is_filterable' => true],
                    ['name' => 'Tipe Memori GPU', 'slug' => 'gpu_memory_type'],
                    ['name' => 'Rekomendasi Daya PSU', 'slug' => 'gpu_recommended_psu', 'unit' => 'Watt', 'is_filterable' => true],
                    ['name' => 'Panjang Kartu Grafis', 'slug' => 'gpu_length', 'unit' => 'mm'],
                ],
            ],
            [
                'name' => 'Motherboard',
                'slug' => 'motherboard',
                'sort_order' => 5,
                'attributes' => [
                    ['name' => 'Chipset Motherboard', 'slug' => 'mb_chipset', 'is_filterable' => true],
                    ['name' => 'Form Factor Motherboard', 'slug' => 'mb_form_factor', 'is_filterable' => true],
                    ['name' => 'Soket CPU Didukung', 'slug' => 'mb_socket', 'is_filterable' => true],
                    ['name' => 'Jumlah Slot RAM', 'slug' => 'mb_ram_slots'],
                    ['name' => 'Dukungan Tipe RAM', 'slug' => 'mb_ram_type', 'is_filterable' => true],
                ],
            ],
            [
                'name' => 'Display Layar',
                'slug' => 'display',
                'sort_order' => 6,
                'attributes' => [
                    ['name' => 'Ukuran Layar', 'slug' => 'display_size', 'unit' => 'Inch', 'is_filterable' => true],
                    ['name' => 'Resolusi Layar', 'slug' => 'display_resolution', 'is_filterable' => true],
                    ['name' => 'Tipe Panel', 'slug' => 'display_panel', 'is_filterable' => true],
                    ['name' => 'Refresh Rate', 'slug' => 'display_refresh_rate', 'unit' => 'Hz', 'is_filterable' => true],
                ],
            ],
            [
                'name' => 'Power Supply Unit (PSU)',
                'slug' => 'psu',
                'sort_order' => 7,
                'attributes' => [
                    ['name' => 'Kapasitas Daya', 'slug' => 'psu_wattage', 'unit' => 'Watt', 'is_filterable' => true],
                    ['name' => 'Sertifikasi Efisiensi', 'slug' => 'psu_efficiency', 'is_filterable' => true],
                    ['name' => 'Tipe Modular', 'slug' => 'psu_modularity'],
                ],
            ],
            [
                'name' => 'Casing PC',
                'slug' => 'casing',
                'sort_order' => 8,
                'attributes' => [
                    ['name' => 'Form Factor Casing', 'slug' => 'case_form_factor', 'is_filterable' => true],
                    ['name' => 'Panjang Maksimal GPU', 'slug' => 'case_max_gpu_length', 'unit' => 'mm', 'is_filterable' => true],
                ],
            ],
            [
                'name' => 'Pendingin CPU (Cooler)',
                'slug' => 'cooler',
                'sort_order' => 9,
                'attributes' => [
                    ['name' => 'Soket Cooler Didukung', 'slug' => 'cooler_socket', 'is_filterable' => true],
                    ['name' => 'Kapasitas Pendinginan TDP', 'slug' => 'cooler_tdp', 'unit' => 'Watt', 'is_filterable' => true],
                ],
            ],
        ];

        $attributesMap = [];
        foreach ($groupsData as $gIndex => $gData) {
            $group = SpecificationGroup::updateOrCreate(
                ['slug' => $gData['slug']],
                [
                    'name' => $gData['name'],
                    'slug' => $gData['slug'],
                    'sort_order' => $gData['sort_order'],
                ]
            );

            foreach ($gData['attributes'] as $aIndex => $aData) {
                $attr = SpecificationAttribute::updateOrCreate(
                    ['slug' => $aData['slug']],
                    [
                        'group_id' => $group->id,
                        'name' => $aData['name'],
                        'slug' => $aData['slug'],
                        'unit' => $aData['unit'] ?? null,
                        'is_filterable' => $aData['is_filterable'] ?? false,
                        'sort_order' => $aIndex + 1,
                    ]
                );
                $attributesMap[$aData['slug']] = $attr->id;
            }
        }

        // Fetch Categories & Brands
        $catLaptop = Category::where('slug', 'laptop')->first();
        $catComponents = Category::where('slug', 'komponen-pc')->first();
        $catMonitor = Category::where('slug', 'monitor')->first();
        $catStorage = Category::where('slug', 'media-penyimpanan')->first();
        $catAccessories = Category::where('slug', 'aksesoris-komputer')->first();

        $brandAsus = Brand::where('slug', 'asus')->first();
        $brandLenovo = Brand::where('slug', 'lenovo')->first();
        $brandIntel = Brand::where('slug', 'intel')->first();
        $brandAmd = Brand::where('slug', 'amd')->first();
        $brandNvidia = Brand::where('slug', 'nvidia')->first();
        $brandSamsung = Brand::where('slug', 'samsung')->first();
        $brandCorsair = Brand::where('slug', 'corsair')->first();
        $brandLogitech = Brand::where('slug', 'logitech')->first();

        // 2. REALISTIC TECHNOLOGY PRODUCTS
        $productsData = [
            [
                'name' => 'ASUS ROG Strix SCAR 16 (2026) G634JZ',
                'category_id' => $catLaptop->id,
                'brand_id' => $brandAsus->id,
                'short_description' => 'Laptop Gaming Flagship dengan Intel Core i9-14900HX, RTX 4080, Mini-LED ROG Nebula HDR Display 240Hz.',
                'description' => 'Taklukkan semua judul game AAA dan beban komputasi terberat dengan ASUS ROG Strix SCAR 16. Ditenagai prosesor Intel Core i9 Generasi ke-14 dan GPU NVIDIA GeForce RTX 4080 Laptop, dilengkapi sistem pendingin Tri-Fan inovatif dan liquid metal Conductonaut Extreme.',
                'warranty_period_months' => 24,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => '32GB RAM / 1TB SSD',
                        'sku' => 'ROG-SCAR16-32-1TB',
                        'price' => 52999000,
                        'cost_price' => 47000000,
                        'stock' => 15,
                        'weight_grams' => 2650,
                        'is_default' => true,
                    ],
                    [
                        'name' => '64GB RAM / 2TB SSD',
                        'sku' => 'ROG-SCAR16-64-2TB',
                        'price' => 61999000,
                        'cost_price' => 55000000,
                        'stock' => 8,
                        'weight_grams' => 2650,
                        'is_default' => false,
                    ],
                ],
                'specs' => [
                    'cpu_socket' => 'FCBGA1964 (Soldered)',
                    'cpu_cores' => '24 (8P + 16E)',
                    'cpu_threads' => '32',
                    'cpu_base_clock' => '2.2',
                    'cpu_boost_clock' => '5.8',
                    'cpu_tdp' => '55',
                    'ram_type' => 'DDR5 5600MHz',
                    'ram_capacity' => '32',
                    'gpu_chipset' => 'NVIDIA GeForce RTX 4080 Laptop',
                    'gpu_vram' => '12',
                    'display_size' => '16.0',
                    'display_resolution' => '2560 x 1600 (QHD+)',
                    'display_panel' => 'Mini-LED ROG Nebula HDR',
                    'display_refresh_rate' => '240',
                ],
            ],
            [
                'name' => 'Lenovo Legion Pro 5 16ARX8 Gaming Laptop',
                'category_id' => $catLaptop->id,
                'brand_id' => $brandLenovo->id,
                'short_description' => 'Laptop Gaming Kencang AMD Ryzen 7 7745HX, RTX 4070 8GB GDDR6, Layar 16" WQXGA 240Hz.',
                'description' => 'Lenovo Legion Pro 5 dirancang khusus untuk gamer kompetitif dan kreator konten. Mengusung prosesor AMD Ryzen 7 Series dan grafis RTX 4070 dengan TGP maksimal 140W serta teknologi pendingin Legion Coldfront 5.0.',
                'warranty_period_months' => 24,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => '16GB RAM / 1TB SSD',
                        'sku' => 'LEGION-PRO5-16-1TB',
                        'price' => 28999000,
                        'cost_price' => 25500000,
                        'stock' => 20,
                        'weight_grams' => 2500,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'cpu_socket' => 'FL1 (Soldered)',
                    'cpu_cores' => '8',
                    'cpu_threads' => '16',
                    'cpu_base_clock' => '3.6',
                    'cpu_boost_clock' => '5.1',
                    'cpu_tdp' => '55',
                    'ram_type' => 'DDR5 5200MHz',
                    'ram_capacity' => '16',
                    'gpu_chipset' => 'NVIDIA GeForce RTX 4070 Laptop',
                    'gpu_vram' => '8',
                    'display_size' => '16.0',
                    'display_resolution' => '2560 x 1600 (WQXGA)',
                    'display_panel' => 'IPS 500 nits 100% sRGB',
                    'display_refresh_rate' => '240',
                ],
            ],
            [
                'name' => 'Prosesor Intel Core i7-14700K Desktop Processor',
                'category_id' => $catComponents->id,
                'brand_id' => $brandIntel->id,
                'short_description' => 'Prosesor Intel Generasi ke-14 Raptor Lake Refresh, 20 Cores (8P + 12E), Soket LGA1700, Up to 5.6 GHz.',
                'description' => 'Prosesor desktop Intel Core i7-14700K memberikan performa luar biasa untuk gaming kompetitif, streaming simultan, dan rendering 3D berkat kombinasi 20 core dan 28 thread serta dukungan DDR4 & DDR5.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => 'Box Resmi Intel Indonesia',
                        'sku' => 'INTEL-I7-14700K-BOX',
                        'price' => 7150000,
                        'cost_price' => 6400000,
                        'stock' => 35,
                        'weight_grams' => 150,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'cpu_socket' => 'LGA1700',
                    'cpu_cores' => '20 (8P + 12E)',
                    'cpu_threads' => '28',
                    'cpu_base_clock' => '3.4',
                    'cpu_boost_clock' => '5.6',
                    'cpu_tdp' => '125',
                ],
            ],
            [
                'name' => 'Prosesor AMD Ryzen 7 7800X3D Desktop Processor',
                'category_id' => $catComponents->id,
                'brand_id' => $brandAmd->id,
                'short_description' => 'Prosesor Gaming Terbaik di Dunia dengan 3D V-Cache Technology, 8 Cores / 16 Threads, Soket AM5.',
                'description' => 'Rajanya prosesor gaming saat ini! AMD Ryzen 7 7800X3D dilengkapi memori cache 96MB 3D V-Cache yang mendongkrak frame-rate (FPS) game secara dramatis dengan efisiensi daya yang sangat tinggi.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => 'Box Resmi AMD Indonesia',
                        'sku' => 'AMD-RYZEN7-7800X3D-BOX',
                        'price' => 6850000,
                        'cost_price' => 6100000,
                        'stock' => 28,
                        'weight_grams' => 150,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'cpu_socket' => 'AM5',
                    'cpu_cores' => '8',
                    'cpu_threads' => '16',
                    'cpu_base_clock' => '4.2',
                    'cpu_boost_clock' => '5.0',
                    'cpu_tdp' => '120',
                ],
            ],
            [
                'name' => 'Kartu Grafis ASUS ROG Strix GeForce RTX 4070 Ti SUPER OC 16GB',
                'category_id' => $catComponents->id,
                'brand_id' => $brandAsus->id,
                'short_description' => 'VGA Gaming High-End 16GB GDDR6X 256-bit, Axial-tech Fans, Metal Exoskeleton, DLSS 3.5.',
                'description' => 'Performa grafis kelas atas untuk resolusi 1440p dan 4K dengan ray-tracing penuh. Desain ROG Strix menghadirkan heatsink ekstra besar dan 3 kipas Axial-tech untuk temperatur operasi yang sangat dingin dan senyap.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => '16GB GDDR6X OC Edition',
                        'sku' => 'ASUS-ROG-RTX4070TIS-16G',
                        'price' => 17499000,
                        'cost_price' => 15800000,
                        'stock' => 12,
                        'weight_grams' => 1800,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'gpu_chipset' => 'NVIDIA GeForce RTX 4070 Ti SUPER',
                    'gpu_vram' => '16',
                    'gpu_memory_type' => 'GDDR6X 256-bit',
                    'gpu_recommended_psu' => '750',
                    'gpu_length' => '336',
                ],
            ],
            [
                'name' => 'Motherboard ASUS ROG STRIX B650-A GAMING WIFI',
                'category_id' => $catComponents->id,
                'brand_id' => $brandAsus->id,
                'short_description' => 'Motherboard Gaming Soket AM5, Form Factor ATX, DDR5, PCIe 5.0 M.2, WiFi 6E, Estetika Putih Silver.',
                'description' => 'Fondasi kokoh untuk rakitan PC AMD Ryzen 7000/8000/9000 Series. Dilengkapi 12+2 power stages, slot PCIe 5.0 M.2 berkecepatan tinggi, dan konektivitas WiFi 6E terkini.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => false,
                'variants' => [
                    [
                        'name' => 'Standard Retail Box',
                        'sku' => 'ASUS-ROG-B650A-WIFI',
                        'price' => 4650000,
                        'cost_price' => 4100000,
                        'stock' => 18,
                        'weight_grams' => 1500,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'mb_chipset' => 'AMD B650',
                    'mb_form_factor' => 'ATX',
                    'mb_socket' => 'AM5',
                    'mb_ram_slots' => '4',
                    'mb_ram_type' => 'DDR5',
                ],
            ],
            [
                'name' => 'Memori RAM Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz CL30',
                'category_id' => $catComponents->id,
                'brand_id' => $brandCorsair->id,
                'short_description' => 'Kit Memori Dual Channel DDR5 32GB (2x16GB) 6000MHz CL30 dengan Pencahayaan Dynamic Ten-Zone RGB.',
                'description' => 'Performa frekuensi tinggi dan timing rapat CL30 yang dioptimalkan untuk platform Intel XMP 3.0 dan AMD EXPO. Dilengkapi heatspreader aluminium untuk pembuangan panas optimal.',
                'warranty_period_months' => 120, // Lifetime warranty (10 years)
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => 'Black Edition (2x16GB)',
                        'sku' => 'CORSAIR-VEN-RGB-32G-6000',
                        'price' => 2199000,
                        'cost_price' => 1850000,
                        'stock' => 45,
                        'weight_grams' => 120,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'White Edition (2x16GB)',
                        'sku' => 'CORSAIR-VEN-RGB-32G-6000-W',
                        'price' => 2249000,
                        'cost_price' => 1890000,
                        'stock' => 25,
                        'weight_grams' => 120,
                        'is_default' => false,
                    ],
                ],
                'specs' => [
                    'ram_type' => 'DDR5',
                    'ram_capacity' => '32',
                    'ram_speed' => '6000',
                    'ram_channel' => 'Dual Channel (2 x 16GB)',
                ],
            ],
            [
                'name' => 'SSD Samsung 990 PRO NVMe M.2 SSD 1TB PCIe 4.0',
                'category_id' => $catStorage->id,
                'brand_id' => $brandSamsung->id,
                'short_description' => 'SSD NVMe Gen 4.0 Terbaik Kecepatan Baca Hingga 7.450 MB/s dan Tulis Hingga 6.900 MB/s.',
                'description' => 'Mencapai batas performa PCIe 4.0. Kontroler Pascal dan V-NAND generasi terbaru dari Samsung memberikan kecepatan transfer data luar biasa serta efisiensi daya termal terbaik.',
                'warranty_period_months' => 60,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => '1TB Non-Heatsink',
                        'sku' => 'SAMSUNG-990PRO-1TB',
                        'price' => 1890000,
                        'cost_price' => 1600000,
                        'stock' => 50,
                        'weight_grams' => 50,
                        'is_default' => true,
                    ],
                    [
                        'name' => '2TB Non-Heatsink',
                        'sku' => 'SAMSUNG-990PRO-2TB',
                        'price' => 3290000,
                        'cost_price' => 2850000,
                        'stock' => 30,
                        'weight_grams' => 50,
                        'is_default' => false,
                    ],
                ],
                'specs' => [
                    'storage_interface' => 'PCIe Gen 4.0 x4, NVMe 2.0',
                    'storage_form_factor' => 'M.2 2280',
                    'storage_capacity' => '1000',
                    'storage_read_speed' => '7450',
                    'storage_write_speed' => '6900',
                ],
            ],
            [
                'name' => 'Power Supply Corsair RM850e 850W 80 Plus Gold Fully Modular',
                'category_id' => $catComponents->id,
                'brand_id' => $brandCorsair->id,
                'short_description' => 'PSU ATX 3.0 & PCIe 5.0 850 Watt, Sertifikasi 80 PLUS Gold, Cybenetics Platinum, 105°C Capacitors.',
                'description' => 'Catu daya handal untuk kartu grafis RTX 40 Series dengan kabel daya 12VHPWR native. Pengoperasian senyap dengan kipas 120mm rifle bearing dan mode Zero RPM fan.',
                'warranty_period_months' => 84, // 7 years
                'status' => 'active',
                'is_featured' => false,
                'variants' => [
                    [
                        'name' => '850 Watt Standard',
                        'sku' => 'CORSAIR-RM850E-850W',
                        'price' => 1999000,
                        'cost_price' => 1700000,
                        'stock' => 35,
                        'weight_grams' => 1600,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'psu_wattage' => '850',
                    'psu_efficiency' => '80 PLUS Gold',
                    'psu_modularity' => 'Fully Modular',
                ],
            ],
            [
                'name' => 'Monitor Gaming Samsung Odyssey OLED G8 34" Curved WQHD 175Hz',
                'category_id' => $catMonitor->id,
                'brand_id' => $brandSamsung->id,
                'short_description' => 'Monitor Gaming Layar Lengkung 34 Inch QD-OLED, Ultra WQHD 3440x1440, Waktu Respon 0.03ms, 175Hz.',
                'description' => 'Pengalaman visual imersif tanpa tanding. Panel QD-OLED menghadirkan warna hitam pekat sempurna, kontras tak terbatas, dan kecerahan spektakuler dengan sertifikasi DisplayHDR True Black 400.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => '34 Inch Curved OLED',
                        'sku' => 'SAMSUNG-G8-OLED-34',
                        'price' => 18499000,
                        'cost_price' => 16200000,
                        'stock' => 10,
                        'weight_grams' => 7500,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'display_size' => '34.0',
                    'display_resolution' => '3440 x 1440 (Ultra WQHD)',
                    'display_panel' => 'QD-OLED Curved 1800R',
                    'display_refresh_rate' => '175',
                ],
            ],
            [
                'name' => 'Mouse Gaming Logitech G PRO X SUPERLIGHT 2 Wireless',
                'category_id' => $catAccessories->id,
                'brand_id' => $brandLogitech->id,
                'short_description' => 'Mouse Gaming Esports Terringan 60g, Sensor HERO 2 32.000 DPI, Switch Hybrid LIGHTFORCE, Polling Rate 2000Hz.',
                'description' => 'Pilihan nomor satu para atlet profesional esports dunia. Bobot ultra ringan 60 gram tanpa lubang honeycomb, sensor optik paling presisi di kelasnya, dan daya tahan baterai hingga 95 jam.',
                'warranty_period_months' => 24,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => 'Warna Hitam (Black)',
                        'sku' => 'LOGITECH-GPX2-BLK',
                        'price' => 2090000,
                        'cost_price' => 1750000,
                        'stock' => 40,
                        'weight_grams' => 60,
                        'is_default' => true,
                    ],
                    [
                        'name' => 'Warna Putih (White)',
                        'sku' => 'LOGITECH-GPX2-WHT',
                        'price' => 2090000,
                        'cost_price' => 1750000,
                        'stock' => 30,
                        'weight_grams' => 60,
                        'is_default' => false,
                    ],
                ],
                'specs' => [],
            ],
            [
                'name' => 'Motherboard ASUS TUF GAMING Z790-PLUS WIFI',
                'category_id' => $catComponents->id,
                'brand_id' => $brandAsus->id,
                'short_description' => 'Motherboard Intel Soket LGA1700, Form Factor ATX, DDR5, PCIe 5.0, 4x M.2 Slots, WiFi 6E.',
                'description' => 'Motherboard tangguh standar militer TUF untuk prosesor Intel Core Generasi 13 & 14 dengan 16+1 DrMOS power stages dan pendingin VRM masif.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => false,
                'variants' => [
                    [
                        'name' => 'Standard Box',
                        'sku' => 'ASUS-TUF-Z790-WIFI',
                        'price' => 4850000,
                        'cost_price' => 4200000,
                        'stock' => 20,
                        'weight_grams' => 1500,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'mb_chipset' => 'Intel Z790',
                    'mb_form_factor' => 'ATX',
                    'mb_socket' => 'LGA1700',
                    'mb_ram_slots' => '4',
                    'mb_ram_type' => 'DDR5',
                ],
            ],
            [
                'name' => 'Motherboard ASUS PRIME B760M-A D4',
                'category_id' => $catComponents->id,
                'brand_id' => $brandAsus->id,
                'short_description' => 'Motherboard Intel Soket LGA1700, Form Factor Micro-ATX, Dukungan Memori DDR4, Dual M.2 PCIe 4.0.',
                'description' => 'Solusi hemat bertenaga untuk prosesor Intel Core Generasi 12/13/14 dengan memori DDR4 ekonomis.',
                'warranty_period_months' => 36,
                'status' => 'active',
                'is_featured' => false,
                'variants' => [
                    [
                        'name' => 'Standard Box',
                        'sku' => 'ASUS-PRIME-B760M-D4',
                        'price' => 2250000,
                        'cost_price' => 1950000,
                        'stock' => 25,
                        'weight_grams' => 1200,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'mb_chipset' => 'Intel B760',
                    'mb_form_factor' => 'Micro-ATX',
                    'mb_socket' => 'LGA1700',
                    'mb_ram_slots' => '4',
                    'mb_ram_type' => 'DDR4',
                ],
            ],
            [
                'name' => 'Memori RAM Corsair Vengeance LPX 16GB (2x8GB) DDR4 3200MHz',
                'category_id' => $catComponents->id,
                'brand_id' => $brandCorsair->id,
                'short_description' => 'Kit Memori Dual Channel DDR4 16GB (2x8GB) 3200MHz CL16 Low Profile Heatspreader.',
                'description' => 'Didesain untuk overclocking performa tinggi dengan kompatibilitas luas di platform Intel dan AMD DDR4.',
                'warranty_period_months' => 120,
                'status' => 'active',
                'is_featured' => false,
                'variants' => [
                    [
                        'name' => 'Black 2x8GB DDR4',
                        'sku' => 'CORSAIR-LPX-16G-D4-3200',
                        'price' => 750000,
                        'cost_price' => 620000,
                        'stock' => 50,
                        'weight_grams' => 100,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'ram_type' => 'DDR4',
                    'ram_capacity' => '16',
                    'ram_speed' => '3200',
                    'ram_channel' => 'Dual Channel (2 x 8GB)',
                ],
            ],
            [
                'name' => 'Casing PC Corsair 4000D AIRFLOW Tempered Glass Mid-Tower',
                'category_id' => $catComponents->id,
                'brand_id' => $brandCorsair->id,
                'short_description' => 'Casing PC Mid-Tower Airflow Maksimal, Panel Kaca Tempered, Mendukung Motherboard ATX, GPU hingga 360mm.',
                'description' => 'Sirkulasi udara optimal dengan panel depan berpori, manajemen kabel RapidRoute yang rapi, dan ruang luas untuk kartu grafis high-end.',
                'warranty_period_months' => 24,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => 'Black Edition',
                        'sku' => 'CORSAIR-4000D-AIR-BLK',
                        'price' => 1450000,
                        'cost_price' => 1200000,
                        'stock' => 30,
                        'weight_grams' => 7800,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'case_form_factor' => 'ATX, Micro-ATX, Mini-ITX',
                    'case_max_gpu_length' => '360',
                ],
            ],
            [
                'name' => 'Liquid CPU Cooler Corsair iCUE LINK H150i RGB 360mm',
                'category_id' => $catComponents->id,
                'brand_id' => $brandCorsair->id,
                'short_description' => 'Sistem Pendingin Cair AIO 360mm, Pompa Efisiensi Tinggi, Kompatibel Soket AM5 & LGA1700, TDP 300W.',
                'description' => 'Pendinginan ekstrem untuk prosesor flagship dengan kabel tunggal revolusioner iCUE LINK dan 3 kipas QX120 RGB.',
                'warranty_period_months' => 60,
                'status' => 'active',
                'is_featured' => true,
                'variants' => [
                    [
                        'name' => '360mm Liquid Cooler',
                        'sku' => 'CORSAIR-H150I-LINK-360',
                        'price' => 3890000,
                        'cost_price' => 3300000,
                        'stock' => 20,
                        'weight_grams' => 2200,
                        'is_default' => true,
                    ],
                ],
                'specs' => [
                    'cooler_socket' => 'AM5, AM4, LGA1700, LGA1200',
                    'cooler_tdp' => '300',
                ],
            ],
        ];

        foreach ($productsData as $pData) {
            $slug = Str::slug($pData['name']);
            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $pData['category_id'],
                    'brand_id' => $pData['brand_id'],
                    'name' => $pData['name'],
                    'slug' => $slug,
                    'short_description' => $pData['short_description'],
                    'description' => $pData['description'],
                    'warranty_period_months' => $pData['warranty_period_months'],
                    'status' => $pData['status'],
                    'is_featured' => $pData['is_featured'],
                ]
            );

            // Variants
            foreach ($pData['variants'] as $vData) {
                ProductVariant::updateOrCreate(
                    ['sku' => $vData['sku']],
                    [
                        'product_id' => $product->id,
                        'name' => $vData['name'],
                        'sku' => $vData['sku'],
                        'price' => $vData['price'],
                        'cost_price' => $vData['cost_price'],
                        'stock' => $vData['stock'],
                        'weight_grams' => $vData['weight_grams'],
                        'is_default' => $vData['is_default'],
                        'is_active' => true,
                    ]
                );
            }

            // Specifications
            if (! empty($pData['specs'])) {
                foreach ($pData['specs'] as $attrSlug => $specValue) {
                    if (isset($attributesMap[$attrSlug])) {
                        ProductSpecification::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'attribute_id' => $attributesMap[$attrSlug],
                            ],
                            [
                                'value' => $specValue,
                            ]
                        );
                    }
                }
            }
        }
    }
}
