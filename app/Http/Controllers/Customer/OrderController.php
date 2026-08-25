<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected ShippingService $shippingService
    ) {}
    /**
     * Display list of customer's own orders.
     */
    public function index(Request $request): View
    {
        $status = $request->input('status');

        $orders = $request->user()->orders()
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->with(['items'])
            ->paginate(10)
            ->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show detailed order information and payment instructions.
     */
    public function show(Request $request, string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.variant.product.brand', 'payment'])
            ->firstOrFail();

        // Enforce authorization: Order must belong to the logged-in customer (or admin)
        if ($order->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke rincian pesanan ini.');
        }

        // Get tracking information if tracking number exists
        $trackingInfo = null;
        if ($order->shipping_tracking_number && $order->shipping_courier) {
            $trackingInfo = $this->shippingService->trackShipment(
                $order->shipping_tracking_number,
                $order->shipping_courier
            );
        }

        return view('orders.show', compact('order', 'trackingInfo'));
    }
}
