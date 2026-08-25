<?php

namespace App\Http\Controllers;

use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    public function __construct(
        protected ShippingService $shippingService
    ) {}

    /**
     * Calculate shipping cost for checkout
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calculateCost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'origin_city' => 'required|string',
            'origin_district' => 'required|string',
            'destination_city' => 'required|string',
            'destination_district' => 'required|string',
            'weight_grams' => 'required|integer|min:1',
            'courier' => 'required|string|in:jne,jnt,sicepat',
        ]);

        $origin = [
            'city' => $validated['origin_city'],
            'district' => $validated['origin_district'],
        ];

        $destination = [
            'city' => $validated['destination_city'],
            'district' => $validated['destination_district'],
        ];

        $result = $this->shippingService->calculateShippingCost(
            $origin,
            $destination,
            $validated['weight_grams'],
            $validated['courier']
        );

        return response()->json($result);
    }

    /**
     * Get available couriers
     *
     * @return JsonResponse
     */
    public function getCouriers(): JsonResponse
    {
        $couriers = $this->shippingService->getAvailableCouriers();
        return response()->json([
            'success' => true,
            'couriers' => $couriers,
        ]);
    }

    /**
     * Get available services for a courier
     *
     * @param string $courier
     * @return JsonResponse
     */
    public function getServices(string $courier): JsonResponse
    {
        $services = $this->shippingService->getAvailableServices($courier);
        return response()->json([
            'success' => true,
            'courier' => $courier,
            'services' => $services,
        ]);
    }

    /**
     * Track shipment
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function track(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'tracking_number' => 'required|string',
            'courier' => 'required|string|in:jne,jnt,sicepat',
        ]);

        $result = $this->shippingService->trackShipment(
            $validated['tracking_number'],
            $validated['courier']
        );

        return response()->json($result);
    }
}