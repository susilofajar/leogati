<?php

namespace App\Services;

use App\Services\Contracts\ShippingProviderInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ShippingService implements ShippingProviderInterface
{
    /**
     * Calculate shipping cost based on origin, destination, and weight
     *
     * @param array $origin
     * @param array $destination
     * @param int $weightGrams
     * @param string $courier
     * @return array
     */
    public function calculateShippingCost(array $origin, array $destination, int $weightGrams, string $courier): array
    {
        try {
            // For development, use mock rates. In production, integrate with real APIs
            $rates = $this->getMockShippingRates($courier, $weightGrams);

            return [
                'success' => true,
                'courier' => $courier,
                'origin' => $origin,
                'destination' => $destination,
                'weight_grams' => $weightGrams,
                'services' => $rates,
            ];

        } catch (\Exception $e) {
            Log::error('Shipping Cost Calculation Error: ' . $e->getMessage(), [
                'courier' => $courier,
                'weight' => $weightGrams,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Track shipment status
     *
     * @param string $trackingNumber
     * @param string $courier
     * @return array
     */
    public function trackShipment(string $trackingNumber, string $courier): array
    {
        try {
            // For development, use mock tracking data
            $trackingData = $this->getMockTrackingData($trackingNumber, $courier);

            return [
                'success' => true,
                'tracking_number' => $trackingNumber,
                'courier' => $courier,
                'history' => $trackingData,
            ];

        } catch (\Exception $e) {
            Log::error('Shipment Tracking Error: ' . $e->getMessage(), [
                'tracking_number' => $trackingNumber,
                'courier' => $courier,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get available couriers
     *
     * @return array
     */
    public function getAvailableCouriers(): array
    {
        return [
            'jne' => [
                'name' => 'JNE',
                'logo' => '/images/couriers/jne.png',
                'is_active' => true,
            ],
            'jnt' => [
                'name' => 'J&T Express',
                'logo' => '/images/couriers/jnt.png',
                'is_active' => true,
            ],
            'sicepat' => [
                'name' => 'SiCepat',
                'logo' => '/images/couriers/sicepat.png',
                'is_active' => true,
            ],
        ];
    }

    /**
     * Get available services for a courier
     *
     * @param string $courier
     * @return array
     */
    public function getAvailableServices(string $courier): array
    {
        $services = [
            'jne' => [
                'JNE REG' => [
                    'name' => 'JNE Regular',
                    'description' => 'Layanan reguler',
                    'estimated_days' => 2-3,
                ],
                'JNE YES' => [
                    'name' => 'JNE YES',
                    'description' => 'Yakin Esok Sampai',
                    'estimated_days' => 1,
                ],
                'JNE OKE' => [
                    'name' => 'JNE OKE',
                    'description' => 'Ongkos Kirim Ekonomis',
                    'estimated_days' => 3-5,
                ],
            ],
            'jnt' => [
                'J&T EZ' => [
                    'name' => 'J&T EZ',
                    'description' => 'Layanan ekonomis',
                    'estimated_days' => 3-4,
                ],
                'J&T Express' => [
                    'name' => 'J&T Express',
                    'description' => 'Layanan reguler',
                    'estimated_days' => 2-3,
                ],
            ],
            'sicepat' => [
                'SiCepat BEST' => [
                    'name' => 'SiCepat BEST',
                    'description' => 'Besok Sampai Tujuan',
                    'estimated_days' => 1,
                ],
                'SiCepat REG' => [
                    'name' => 'SiCepat REG',
                    'description' => 'Layanan reguler',
                    'estimated_days' => 2-3,
                ],
            ],
        ];

        return $services[$courier] ?? [];
    }

    /**
     * Get mock shipping rates for development
     * In production, replace with real API calls to shipping providers
     *
     * @param string $courier
     * @param int $weightGrams
     * @return array
     */
    protected function getMockShippingRates(string $courier, int $weightGrams): array
    {
        $weightKg = max(1, ceil($weightGrams / 1000));

        $baseRates = [
            'jne' => [
                'JNE REG' => 12000,
                'JNE YES' => 20000,
                'JNE OKE' => 9000,
            ],
            'jnt' => [
                'J&T EZ' => 10000,
                'J&T Express' => 14000,
            ],
            'sicepat' => [
                'SiCepat BEST' => 22000,
                'SiCepat REG' => 13000,
            ],
        ];

        $courierRates = $baseRates[$courier] ?? $baseRates['jne'];

        $services = [];
        foreach ($courierRates as $serviceCode => $baseRate) {
            $services[] = [
                'service_code' => $serviceCode,
                'service_name' => $this->getAvailableServices($courier)[$serviceCode]['name'] ?? $serviceCode,
                'description' => $this->getAvailableServices($courier)[$serviceCode]['description'] ?? '',
                'estimated_days' => $this->getAvailableServices($courier)[$serviceCode]['estimated_days'] ?? 2-3,
                'cost' => $baseRate * $weightKg,
                'cost_formatted' => 'Rp ' . number_format($baseRate * $weightKg, 0, ',', '.'),
            ];
        }

        return $services;
    }

    /**
     * Get mock tracking data for development
     * In production, replace with real API calls to shipping providers
     *
     * @param string $trackingNumber
     * @param string $courier
     * @return array
     */
    protected function getMockTrackingData(string $trackingNumber, string $courier): array
    {
        // Generate realistic mock tracking history
        $history = [
            [
                'date' => now()->subDays(3)->format('Y-m-d H:i:s'),
                'status' => 'picked_up',
                'description' => 'Paket telah diambil oleh kurir',
                'location' => 'Jakarta Pusat',
            ],
            [
                'date' => now()->subDays(2)->format('Y-m-d H:i:s'),
                'status' => 'in_transit',
                'description' => 'Paket dalam perjalanan',
                'location' => 'Hub Jakarta',
            ],
            [
                'date' => now()->subDay()->format('Y-m-d H:i:s'),
                'status' => 'in_transit',
                'description' => 'Paket tiba di kota tujuan',
                'location' => 'Surabaya',
            ],
            [
                'date' => now()->format('Y-m-d H:i:s'),
                'status' => 'delivered',
                'description' => 'Paket telah diterima',
                'location' => 'Surabaya',
            ],
        ];

        return $history;
    }

    /**
     * Format tracking status to Indonesian
     *
     * @param string $status
     * @return string
     */
    public function formatTrackingStatus(string $status): string
    {
        return match ($status) {
            'picked_up' => 'Diambil Kurir',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Terkirim',
            'failed' => 'Gagal Kirim',
            'returned' => 'Dikembalikan',
            default => 'Status Tidak Diketahui',
        };
    }
}