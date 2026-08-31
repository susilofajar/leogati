<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use Illuminate\Database\Seeder;

class HeroBannerSeeder extends Seeder
{
    public function run(): void
    {
        $slides = [
            [
                'title'       => 'Solusi Lengkap Komputer, Laptop & PC Rakitan Masa Depan',
                'subtitle'    => 'Temukan berbagai perangkat laptop terbaik, komponen PC original, simulasi rakit PC dengan mesin kompatibilitas cerdas, dan pengecekan garansi resmi yang transparan.',
                'badge_text'  => 'Platform E-Commerce Teknologi Resmi Indonesia',
                'image_path'  => 'images/hero/hero-bg-1.jpg',
                'button_text' => 'Mulai Simulasi PC Builder',
                'button_url'  => route('pc_builder.index', [], false),
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'title'       => 'Komponen PC High-Performance & Kartu Grafis Terbaru',
                'subtitle'    => 'Dapatkan prosesor generasi terbaru, GPU RTX Series, RAM DDR5, dan SSD NVMe Gen4 bergaransi distributor resmi di Kendal, Jawa Tengah.',
                'badge_text'  => 'Garansi 100% Original & Resmi',
                'image_path'  => 'images/hero/hero-bg-2.jpg',
                'button_text' => 'Jelajahi Produk Kataloog',
                'button_url'  => route('products.index', [], false),
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'title'       => 'Pusat Layanan Garansi Transparan & Uji Kompatibilitas Hardware',
                'subtitle'    => 'Periksa nomor seri produk Anda secara instan dan rasakan kemudahan klaim garansi tanpa ribet langsung dari teknisi terpercaya.',
                'badge_text'  => 'Dukungan Teknis & Garansi Cepat',
                'image_path'  => 'images/hero/hero-bg-3.jpg',
                'button_text' => 'Cek Garansi Nomor Seri',
                'button_url'  => route('warranty.check', [], false),
                'sort_order'  => 3,
                'is_active'   => true,
            ],
        ];

        foreach ($slides as $slide) {
            HeroBanner::firstOrCreate(
                ['title' => $slide['title']],
                $slide
            );
        }
    }
}
