<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PcBuilderCompatibilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PcBuilderApiController extends Controller
{
    public function __construct(
        protected PcBuilderCompatibilityService $compatService
    ) {}

    /**
     * Validasi kompatibilitas racikan hardware PC dan kalkulasi estimasi kebutuhan daya.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'components' => ['required', 'array'],
            'components.*' => ['nullable', 'integer', 'exists:product_variants,id'],
        ]);

        $components = $request->input('components', []);
        $evaluation = $this->compatService->evaluateBuild($components);

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_price' => $evaluation['total_price'],
                'formatted_total_price' => rupiah($evaluation['total_price']),
                'compatibility' => [
                    'status' => $evaluation['status'],
                    'status_label' => $evaluation['status_label'],
                    'is_compatible' => $evaluation['status'] === 'compatible',
                    'messages' => $evaluation['messages'],
                ],
                'power' => [
                    'estimated_wattage' => $evaluation['estimated_wattage'],
                    'recommended_psu' => $evaluation['recommended_psu'],
                ],
            ],
        ]);
    }
}
