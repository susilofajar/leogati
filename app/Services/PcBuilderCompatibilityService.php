<?php

namespace App\Services;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class PcBuilderCompatibilityService
{
    /**
     * Evaluasi kompatibilitas dan kalkulasi konsumsi daya untuk rakitan komponen PC.
     *
     * @param  array  $selectedVariantIds  Contoh: ['cpu' => 5, 'motherboard' => 6, 'ram' => 7, ...]
     * @return array
     */
    public function evaluateBuild(array $selectedVariantIds): array
    {
        // 1. Muat seluruh varian yang dipilih beserta spesifikasinya
        $variantIds = array_filter(array_values($selectedVariantIds));
        $variants = ProductVariant::with(['product.specifications.attribute'])
            ->whereIn('id', $variantIds)
            ->get()
            ->keyBy(function ($item) use ($selectedVariantIds) {
                foreach ($selectedVariantIds as $slot => $id) {
                    if ((int) $id === (int) $item->id) {
                        return $slot;
                    }
                }
                return 'unknown_' . $item->id;
            });

        $messages = [];
        $hasIncompatible = false;
        $hasWarning = false;

        $cpu         = $variants->get('cpu');
        $motherboard = $variants->get('motherboard');
        $ram         = $variants->get('ram');
        $gpu         = $variants->get('gpu');
        $storage     = $variants->get('storage');
        $psu         = $variants->get('psu');
        $casing      = $variants->get('casing');
        $cooler      = $variants->get('cooler');

        // Total harga komponen
        $totalPrice = (float) $variants->sum('price');

        // 2. Kalkulasi Daya (Power / Wattage)
        $cpuTdp = $cpu ? (int) $this->getSpec($cpu, 'cpu_tdp', 65) : 0;
        $gpuRecPsu = $gpu ? (int) $this->getSpec($gpu, 'gpu_recommended_psu', 500) : 0;
        // Estimasi konsumsi GPU ~ 55% dari rekomendasi PSU pabrikan atau minimal 75W
        $gpuTdp = $gpu ? max(75, (int) ($gpuRecPsu * 0.45)) : 0;
        $baseDraw = ($motherboard ? 40 : 0) + ($ram ? 15 : 0) + ($storage ? 10 : 0) + 15; // Motherboard + RAM + SSD + Fans
        $coolerTdp = $cooler ? 15 : 0;

        $estimatedWattage = $cpuTdp + $gpuTdp + $baseDraw + $coolerTdp;
        // Rekomendasi PSU dengan buffer keamanan 30% dibulatkan ke kelipatan 50W
        $recommendedPsu = $estimatedWattage > 0 ? (int) (ceil(($estimatedWattage * 1.35) / 50) * 50) : 450;
        if ($gpuRecPsu > $recommendedPsu) {
            $recommendedPsu = $gpuRecPsu;
        }

        // 3. ATURAN KOMPATIBILITAS 1: CPU <-> Motherboard (Soket)
        if ($cpu && $motherboard) {
            $cpuSocket = strtoupper(trim($this->getSpec($cpu, 'cpu_socket', '')));
            $mbSocket  = strtoupper(trim($this->getSpec($motherboard, 'mb_socket', '')));

            if ($cpuSocket && $mbSocket) {
                if ($cpuSocket !== $mbSocket) {
                    $hasIncompatible = true;
                    $messages[] = [
                        'type'    => 'incompatible',
                        'title'   => 'Soket CPU & Motherboard Tidak Cocok',
                        'message' => "Prosesor '{$cpu->product->name}' membutuhkan soket {$cpuSocket}, sedangkan Motherboard '{$motherboard->product->name}' menggunakan soket {$mbSocket}.",
                    ];
                } else {
                    $messages[] = [
                        'type'    => 'compatible',
                        'title'   => 'Soket CPU & Motherboard Cocok',
                        'message' => "Soket CPU ({$cpuSocket}) cocok sempurna dengan soket Motherboard ({$mbSocket}).",
                    ];
                }
            }
        }

        // 4. ATURAN KOMPATIBILITAS 2: Motherboard <-> RAM (Tipe Memori DDR4 / DDR5)
        if ($motherboard && $ram) {
            $mbRamType  = strtoupper(trim($this->getSpec($motherboard, 'mb_ram_type', '')));
            $ramType    = strtoupper(trim($this->getSpec($ram, 'ram_type', '')));

            if ($mbRamType && $ramType) {
                if ($mbRamType !== $ramType && ! str_contains($mbRamType, $ramType)) {
                    $hasIncompatible = true;
                    $messages[] = [
                        'type'    => 'incompatible',
                        'title'   => 'Tipe Memori RAM Tidak Didukung',
                        'message' => "Motherboard '{$motherboard->product->name}' hanya mendukung memori {$mbRamType}, sedangkan RAM '{$ram->product->name}' bertipe {$ramType}.",
                    ];
                } else {
                    $messages[] = [
                        'type'    => 'compatible',
                        'title'   => 'Tipe RAM Sesuai',
                        'message' => "Tipe memori RAM ({$ramType}) didukung penuh oleh Motherboard.",
                    ];
                }
            }
        }

        // 5. ATURAN KOMPATIBILITAS 3: Power Supply (PSU) vs Konsumsi Daya & Rekomendasi GPU
        if ($psu && $estimatedWattage > 0) {
            $psuWattage = (int) $this->getSpec($psu, 'psu_wattage', 0);

            if ($psuWattage > 0) {
                if ($psuWattage < $estimatedWattage) {
                    $hasIncompatible = true;
                    $messages[] = [
                        'type'    => 'incompatible',
                        'title'   => 'Kapasitas Daya Power Supply Kurang',
                        'message' => "Daya PSU pilihan ({$psuWattage} Watt) tidak mencukupi untuk kebutuhan sistem (Estimasi: {$estimatedWattage} Watt). Dibutuhkan minimal {$recommendedPsu} Watt.",
                    ];
                } elseif ($gpuRecPsu > 0 && $psuWattage < $gpuRecPsu) {
                    $hasWarning = true;
                    $messages[] = [
                        'type'    => 'warning',
                        'title'   => 'Peringatan Daya PSU di Bawah Rekomendasi Pabrikan GPU',
                        'message' => "PSU pilihan memiliki daya {$psuWattage} Watt. Pabrikan kartu grafis merekomendasikan minimal {$gpuRecPsu} Watt untuk stabilitas beban puncak.",
                    ];
                } else {
                    $messages[] = [
                        'type'    => 'compatible',
                        'title'   => 'Kapasitas Power Supply Memadai',
                        'message' => "Power Supply ({$psuWattage} Watt) sangat mencukupi kebutuhan daya sistem ({$estimatedWattage} Watt).",
                    ];
                }
            }
        }

        // 6. ATURAN KOMPATIBILITAS 4: Cooler <-> Soket CPU
        if ($cpu && $cooler) {
            $cpuSocket = strtoupper(trim($this->getSpec($cpu, 'cpu_socket', '')));
            $coolerSocket = strtoupper(trim($this->getSpec($cooler, 'cooler_socket', '')));

            if ($cpuSocket && $coolerSocket) {
                if (! str_contains($coolerSocket, $cpuSocket)) {
                    $hasIncompatible = true;
                    $messages[] = [
                        'type'    => 'incompatible',
                        'title'   => 'Pendingin CPU Tidak Mendukung Soket',
                        'message' => "Cooler '{$cooler->product->name}' tidak mendukung soket {$cpuSocket} pada prosesor yang dipilih.",
                    ];
                } else {
                    $messages[] = [
                        'type'    => 'compatible',
                        'title'   => 'Bracket Cooler Mendukung Soket CPU',
                        'message' => "Pendingin CPU mendukung soket {$cpuSocket}.",
                    ];
                }
            }
        }

        // 7. ATURAN KOMPATIBILITAS 5: Kartu Grafis (GPU) <-> Panjang Casing
        if ($gpu && $casing) {
            $gpuLength = (int) $this->getSpec($gpu, 'gpu_length', 0);
            $caseMaxGpu = (int) $this->getSpec($casing, 'case_max_gpu_length', 0);

            if ($gpuLength > 0 && $caseMaxGpu > 0) {
                if ($gpuLength > $caseMaxGpu) {
                    $hasIncompatible = true;
                    $messages[] = [
                        'type'    => 'incompatible',
                        'title'   => 'Ukuran GPU Terlalu Panjang untuk Casing',
                        'message' => "Panjang GPU ({$gpuLength} mm) melebihi batas ruang maksimal Casing ({$caseMaxGpu} mm).",
                    ];
                } else {
                    $messages[] = [
                        'type'    => 'compatible',
                        'title'   => 'Panjang GPU Muat di Casing',
                        'message' => "Panjang GPU ({$gpuLength} mm) muat di dalam Casing (Maksimal: {$caseMaxGpu} mm).",
                    ];
                }
            }
        }

        // 8. Tentukan Status Keseluruhan
        $overallStatus = 'compatible';
        if ($hasIncompatible) {
            $overallStatus = 'incompatible';
        } elseif ($hasWarning) {
            $overallStatus = 'warning';
        }

        return [
            'status'            => $overallStatus,
            'status_label'      => match ($overallStatus) {
                'compatible'   => 'Kompatibel & Siap Dirakit',
                'warning'      => 'Kompatibel dengan Catatan Peringatan',
                'incompatible' => 'Komponen Tidak Kompatibel',
            },
            'status_color'      => match ($overallStatus) {
                'compatible'   => 'emerald',
                'warning'      => 'amber',
                'incompatible' => 'rose',
            },
            'messages'          => $messages,
            'estimated_wattage' => $estimatedWattage,
            'recommended_psu'   => $recommendedPsu,
            'total_price'       => $totalPrice,
            'selected_variants' => $variants,
        ];
    }

    /**
     * Ambil nilai spesifikasi produk berdasarkan slug atribut.
     */
    protected function getSpec(ProductVariant $variant, string $attributeSlug, mixed $default = null): mixed
    {
        $specs = $variant->product->specifications;
        if (! $specs) {
            return $default;
        }

        $spec = $specs->first(function ($s) use ($attributeSlug) {
            return $s->attribute && $s->attribute->slug === $attributeSlug;
        });

        return $spec ? $spec->value : $default;
    }
}
