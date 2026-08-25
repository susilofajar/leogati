<?php

namespace App\Services\Contracts;

interface ShippingProviderInterface
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
    public function calculateShippingCost(array $origin, array $destination, int $weightGrams, string $courier): array;

    /**
     * Track shipment status
     *
     * @param string $trackingNumber
     * @param string $courier
     * @return array
     */
    public function trackShipment(string $trackingNumber, string $courier): array;

    /**
     * Get available couriers
     *
     * @return array
     */
    public function getAvailableCouriers(): array;

    /**
     * Get available services for a courier
     *
     * @param string $courier
     * @return array
     */
    public function getAvailableServices(string $courier): array;
}