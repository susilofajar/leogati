<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\SerialNumber;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OperationalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'pelanggan@example.com')->first();
        $admin = User::where('email', 'admin@leogati.store')->first();
        $warehouse = Warehouse::where('code', 'GUD-PUSAT')->first();

        // 1. SEED COUPONS
        $coupons = [
            [
                'code'                => 'LEOGATI10',
                'name'                => 'Diskon Launching 10%',
                'type'                => 'percent',
                'value'               => 10,
                'min_purchase_amount' => 500000,
                'max_discount_amount' => 1000000,
                'usage_limit'         => 100,
                'used_count'          => 5,
                'start_date'          => Carbon::now()->subDays(10),
                'end_date'            => Carbon::now()->addMonths(6),
                'is_active'           => true,
            ],
            [
                'code'                => 'DISKON50RB',
                'name'                => 'Potongan Langsung Rp 50.000',
                'type'                => 'fixed',
                'value'               => 50000,
                'min_purchase_amount' => 300000,
                'max_discount_amount' => 50000,
                'usage_limit'         => 200,
                'used_count'          => 12,
                'start_date'          => Carbon::now()->subDays(5),
                'end_date'            => Carbon::now()->addMonths(3),
                'is_active'           => true,
            ],
            [
                'code'                => 'BUILDER2026',
                'name'                => 'Spesial Rakit PC Rp 150.000',
                'type'                => 'fixed',
                'value'               => 150000,
                'min_purchase_amount' => 5000000,
                'max_discount_amount' => 150000,
                'usage_limit'         => 50,
                'used_count'          => 2,
                'start_date'          => Carbon::now()->subDays(2),
                'end_date'            => Carbon::now()->addMonths(12),
                'is_active'           => true,
            ],
        ];

        foreach ($coupons as $c) {
            Coupon::updateOrCreate(['code' => $c['code']], $c);
        }

        // 2. SEED SUPPLIERS
        $suppliers = [
            [
                'code'          => 'SUP-0001',
                'name'          => 'PT ASUS Technology Indonesia',
                'pic_name'      => 'Bambang Sudarsono',
                'email'         => 'sales@asus-indonesia.co.id',
                'phone'         => '021-2998877',
                'address'       => 'Wisma Mulia Lt. 35, Jl. Jend. Gatot Subroto',
                'city'          => 'Jakarta Selatan',
                'province'      => 'DKI Jakarta',
                'postal_code'   => '12710',
                'payment_terms' => 'Tempo 30 Hari',
                'is_active'     => true,
            ],
            [
                'code'          => 'SUP-0002',
                'name'          => 'PT Synnex Metrodata Indonesia',
                'pic_name'      => 'Christine Wijaya',
                'email'         => 'distribution@synnexmetrodata.com',
                'phone'         => '021-29345800',
                'address'       => 'APL Tower Lt. 42, Podomoro City',
                'city'          => 'Jakarta Barat',
                'province'      => 'DKI Jakarta',
                'postal_code'   => '11470',
                'payment_terms' => 'Cash On Delivery / Net 14 Hari',
                'is_active'     => true,
            ],
            [
                'code'          => 'SUP-0003',
                'name'          => 'PT Nusantara Jaya Teknologi',
                'pic_name'      => 'Hendra Setiawan',
                'email'         => 'orders@njt.co.id',
                'phone'         => '021-6288899',
                'address'       => 'Mangga Dua Mall Lt. 5 Blok A No. 10',
                'city'          => 'Jakarta Pusat',
                'province'      => 'DKI Jakarta',
                'postal_code'   => '10730',
                'payment_terms' => 'Tempo 45 Hari',
                'is_active'     => true,
            ],
        ];

        foreach ($suppliers as $s) {
            Supplier::updateOrCreate(['code' => $s['code']], $s);
        }

        // 3. SEED CUSTOMER ADDRESS
        if ($customer) {
            Address::updateOrCreate(
                [
                    'user_id'    => $customer->id,
                    'is_primary' => true,
                ],
                [
                    'recipient_name' => 'Budi Santoso',
                    'phone_number'   => '081234567890',
                    'address_line'   => 'Jl. Anggrek Cendrawasih No. 45, RT 03 / RW 07',
                    'city'           => 'Jakarta Barat',
                    'province'       => 'DKI Jakarta',
                    'postal_code'    => '11480',
                    'is_primary'     => true,
                ]
            );

            // Seed sample wishlist item
            $firstProduct = Product::first();
            if ($firstProduct) {
                Wishlist::firstOrCreate([
                    'user_id'    => $customer->id,
                    'product_id' => $firstProduct->id,
                ]);
            }
        }

        // 4. SEED SAMPLE SERIAL NUMBERS FOR WARRANTY LOOKUP
        $variants = ProductVariant::with('product')->get();
        $sampleSerials = [
            'LGT-ROG-2026001',
            'LGT-ZEN-2026001',
            'LGT-GPU-4070001',
            'LGT-CPU-7800X01',
            'LGT-MON-27001',
            'LGT-SSD-990P001',
            'LGT-RAM-DOM5001',
            'LGT-PSU-RM85001',
        ];

        foreach ($sampleSerials as $idx => $sn) {
            if (isset($variants[$idx])) {
                $var = $variants[$idx];
                $warrantyMonths = $var->product->warranty_period_months ?: 24;
                $soldDate = Carbon::now()->subMonths(3);
                $warrantyExpires = (clone $soldDate)->addMonths($warrantyMonths);

                SerialNumber::updateOrCreate(
                    ['serial_number' => $sn],
                    [
                        'product_variant_id'  => $var->id,
                        'warehouse_id'        => $warehouse?->id,
                        'customer_id'         => $customer?->id,
                        'status'              => 'sold',
                        'purchased_at'        => Carbon::now()->subMonths(4),
                        'sold_at'             => $soldDate,
                        'warranty_expires_at' => $warrantyExpires,
                        'notes'               => 'Unit resmi bergaransi distributor resmi Indonesia.',
                    ]
                );
            }
        }

        // Available serials in stock
        foreach ($variants->take(5) as $idx => $var) {
            $availSn = 'LGT-STK-' . date('Y') . '-' . str_pad($var->id, 4, '0', STR_PAD_LEFT);
            SerialNumber::updateOrCreate(
                ['serial_number' => $availSn],
                [
                    'product_variant_id'  => $var->id,
                    'warehouse_id'        => $warehouse?->id,
                    'status'              => 'available',
                    'purchased_at'        => Carbon::now()->subMonth(),
                    'notes'               => 'Stok siap jual di Gudang Pusat.',
                ]
            );
        }

        // 5. SEED SAMPLE VERIFIED REVIEWS
        $reviewedProducts = Product::take(4)->get();
        $sampleReviews = [
            [
                'rating'  => 5,
                'title'   => 'Performa Sangat Memuaskan & Pengiriman Kilat!',
                'comment' => 'Barang original 100%, segel distributor resmi utuh, packing kayu sangat aman dan rapi. Langsung saya uji benchmark temperaturnya sangat adem.',
                'reply'   => 'Terima kasih banyak atas ulasan dan kepercayaannya berbelanja di LEOGATISTORE! Semoga perangkatnya awet dan menunjang produktivitas Kakak.',
            ],
            [
                'rating'  => 5,
                'title'   => 'Build Quality Premium, Garansi Terverifikasi!',
                'comment' => 'Langsung saya cek nomor serinya di website LEOGATISTORE, garansi resmi 2 tahun terdaftar sempurna. Recommended seller!',
                'reply'   => 'Terima kasih atas konfirmasinya. Layanan purna jual dan klaim garansi selalu kami prioritaskan untuk seluruh pelanggan setia LEOGATISTORE.',
            ],
            [
                'rating'  => 4,
                'title'   => 'Spesifikasi Sesuai Deskripsi, Sangat Mantap!',
                'comment' => 'Cocok banget buat rakitan PC gaming 1440p. FPS stabil di settingan ultra. Pelayanan admin juga sangat responsif saat ditanya kompatibilitas socket.',
                'reply'   => 'Terima kasih atas ulasan positifnya! Tim teknisi kami siap sedia membantu jika ada pertanyaan seputar upgrade atau perakitan hardware.',
            ],
        ];

        foreach ($reviewedProducts as $idx => $prod) {
            if (isset($sampleReviews[$idx]) && $customer) {
                $revData = $sampleReviews[$idx];
                Review::updateOrCreate(
                    [
                        'product_id' => $prod->id,
                        'user_id'    => $customer->id,
                    ],
                    [
                        'rating'               => $revData['rating'],
                        'title'                => $revData['title'],
                        'comment'              => $revData['comment'],
                        'is_verified_purchase' => true,
                        'is_approved'          => true,
                        'admin_reply'          => $revData['reply'],
                        'admin_replied_at'     => Carbon::now()->subDays(2),
                    ]
                );
            }
        }

        // 6. SEED SAMPLE COMPLETED ORDERS FOR DASHBOARD METRICS
        if ($customer && $variants->count() >= 2) {
            $sampleOrderData = [
                [
                    'order_number'    => 'ORD-' . date('Ymd') . '-0001',
                    'status'          => 'completed',
                    'days_ago'        => 5,
                    'variant'         => $variants[0],
                    'qty'             => 1,
                ],
                [
                    'order_number'    => 'ORD-' . date('Ymd') . '-0002',
                    'status'          => 'shipped',
                    'days_ago'        => 2,
                    'variant'         => $variants[1],
                    'qty'             => 1,
                ],
                [
                    'order_number'    => 'ORD-' . date('Ymd') . '-0003',
                    'status'          => 'paid',
                    'days_ago'        => 1,
                    'variant'         => $variants[0],
                    'qty'             => 1,
                ],
            ];

            foreach ($sampleOrderData as $od) {
                $variant = $od['variant'];
                $subtotal = $variant->price * $od['qty'];
                $shipping = 25000;
                $total = $subtotal + $shipping;
                $createdAt = Carbon::now()->subDays($od['days_ago']);

                $order = Order::updateOrCreate(
                    ['order_number' => $od['order_number']],
                    [
                        'user_id'                  => $customer->id,
                        'subtotal_amount'          => $subtotal,
                        'shipping_amount'          => $shipping,
                        'discount_amount'          => 0,
                        'total_amount'             => $total,
                        'status'                   => $od['status'],
                        'payment_method'           => 'qris',
                        'payment_status'           => 'paid',
                        'shipping_courier'         => 'JNE Express',
                        'shipping_service'         => 'REG (Reguler)',
                        'shipping_tracking_number' => $od['status'] === 'shipped' || $od['status'] === 'completed' ? 'JNE889900112233' : null,
                        'shipping_address'         => [
                            'recipient_name' => 'Budi Santoso',
                            'phone_number'   => '081234567890',
                            'address_line'   => 'Jl. Anggrek Cendrawasih No. 45',
                            'city'           => 'Jakarta Barat',
                            'province'       => 'DKI Jakarta',
                            'postal_code'    => '11480',
                        ],
                        'paid_at'                  => $createdAt,
                        'created_at'               => $createdAt,
                        'updated_at'               => $createdAt,
                    ]
                );

                OrderItem::updateOrCreate(
                    [
                        'order_id'           => $order->id,
                        'product_variant_id' => $variant->id,
                    ],
                    [
                        'product_name' => $variant->product->name,
                        'variant_name' => $variant->name,
                        'sku'          => $variant->sku,
                        'unit_price'   => $variant->price,
                        'quantity'     => $od['qty'],
                        'subtotal'     => $subtotal,
                        'weight_grams' => $variant->weight_grams ?: 500,
                    ]
                );

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'payment_number' => 'PAY-' . str_replace('ORD-', '', $od['order_number']),
                        'payment_method' => 'qris',
                        'amount'         => $total,
                        'status'         => 'success',
                        'paid_at'        => $createdAt,
                    ]
                );
            }
        }
    }
}
