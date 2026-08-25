<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    /**
     * Ambil riwayat pesanan milik pelanggan yang sedang terautentikasi.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->orders()
            ->with(['items', 'coupon'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => OrderResource::collection($orders)->response()->getData(true),
        ]);
    }

    /**
     * Ambil detail rincian pesanan spesifik milik pelanggan.
     */
    public function show(Request $request, string $orderNumber): JsonResponse
    {
        $order = $request->user()->orders()
            ->with(['items', 'coupon'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => new OrderResource($order),
        ]);
    }
}
