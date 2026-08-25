<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\AuditLogService;
use App\Services\ShippingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        protected ShippingService $shippingService
    ) {}
    /**
     * Display a listing of all customer orders.
     */
    public function index(Request $request): View
    {
        $q = $request->input('q');
        $status = $request->input('status');
        $paymentStatus = $request->input('payment_status');

        $orders = Order::with(['user', 'items'])
            ->when($q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('order_number', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($paymentStatus, function ($query, $paymentStatus) {
                $query->where('payment_status', $paymentStatus);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order details.
     */
    public function show(int $id): View
    {
        $order = Order::with(['user', 'items.variant.product.brand', 'payment'])
            ->findOrFail($id);

        // Get tracking information if tracking number exists
        $trackingInfo = null;
        if ($order->shipping_tracking_number && $order->shipping_courier) {
            $trackingInfo = $this->shippingService->trackShipment(
                $order->shipping_tracking_number,
                $order->shipping_courier
            );
        }

        return view('admin.orders.show', compact('order', 'trackingInfo'));
    }

    /**
     * Update order status and tracking info.
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:awaiting_payment,paid,processing,packed,shipped,delivered,completed,cancelled,refunded'],
            'payment_status' => ['required', 'in:unpaid,paid,failed,refunded'],
            'shipping_tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        $order = Order::findOrFail($id);

        $updateData = [
            'status' => $request->input('status'),
            'payment_status' => $request->input('payment_status'),
            'shipping_tracking_number' => $request->input('shipping_tracking_number'),
        ];

        if ($request->input('payment_status') === 'paid' && !$order->paid_at) {
            $updateData['paid_at'] = Carbon::now();
            if ($order->payment) {
                $order->payment->update(['status' => 'success', 'paid_at' => Carbon::now()]);
            }
        }

        $previousStatus = $order->status;
        $order->update($updateData);

        // Notify customer
        try {
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderStatusUpdatedNotification($order, $previousStatus));
            }
        } catch (\Throwable $e) {
            // Log or ignore notification failure
        }

        // Record Audit Log
        \App\Services\AuditLogService::log(
            action: 'order_status_updated',
            targetType: 'Order',
            targetId: $order->id,
            payload: [
                'order_number' => $order->order_number,
                'previous_status' => $previousStatus,
                'new_status' => $order->status,
                'payment_status' => $order->payment_status,
                'tracking_number' => $order->shipping_tracking_number,
            ]
        );

        return redirect()->route('admin.pesanan.show', $order->id)
            ->with('success', 'Status pesanan ' . $order->order_number . ' berhasil diperbarui.');
    }
}
