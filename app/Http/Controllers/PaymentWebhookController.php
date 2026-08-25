<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Services\AuditLogService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected MidtransService $midtransService
    ) {}

    /**
     * Handle Midtrans webhook notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleMidtransWebhook(Request $request)
    {
        try {
            // Get the raw JSON payload
            $payload = $request->getContent();
            $signatureKey = $request->header('X-Signature-Key');

            // Verify webhook signature
            $payloadArray = json_decode($payload, true);
            if (!$this->midtransService->verifyWebhookSignature($payloadArray ?? [], $signatureKey ?? '')) {
                Log::warning('Invalid Midtrans webhook signature', [
                    'signature' => $signatureKey,
                    'payload' => $payload,
                ]);

                return response()->json(['status' => 'invalid_signature'], 403);
            }

            // Parse the notification
            $notification = $this->midtransService->parseWebhookNotification($payloadArray ?? []);

            if (!$notification['success']) {
                Log::error('Failed to parse Midtrans webhook', [
                    'error' => $notification['error'],
                    'payload' => $payload,
                ]);

                return response()->json(['status' => 'parse_error'], 400);
            }

            // Process the payment notification
            $this->processPaymentNotification($notification);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Midtrans webhook processing error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Process payment notification and update order status
     *
     * @param array $notification
     * @return void
     */
    protected function processPaymentNotification(array $notification): void
    {
        $orderId = $notification['order_id'];
        $midtransStatus = $notification['status'];
        $internalStatus = $this->midtransService->mapStatus($midtransStatus);

        DB::transaction(function () use ($orderId, $notification, $internalStatus, $midtransStatus) {
            // Find the order
            $order = Order::where('order_number', $orderId)->firstOrFail();

            // Find the payment record
            $payment = Payment::where('order_id', $order->id)->firstOrFail();

            // Update payment status
            $payment->update([
                'status' => $internalStatus,
                'payment_method' => $notification['payment_type'],
                'paid_at' => $notification['settlement_time'] ?? now(),
                'gateway_response' => [
                    'midtrans_status' => $midtransStatus,
                    'payment_type' => $notification['payment_type'],
                    'transaction_time' => $notification['transaction_time'],
                    'settlement_time' => $notification['settlement_time'],
                    'gross_amount' => $notification['gross_amount'],
                    'fraud_status' => $notification['fraud_status'],
                ],
            ]);

            // Update order status based on payment status
            $this->updateOrderStatus($order, $internalStatus);

            // Log the payment status change
            AuditLogService::log(
                action: 'payment_status_updated',
                targetType: 'Payment',
                targetId: $payment->id,
                payload: [
                    'order_number' => $order->order_number,
                    'payment_number' => $payment->payment_number,
                    'old_status' => $payment->getOriginal('status'),
                    'new_status' => $internalStatus,
                    'midtrans_status' => $midtransStatus,
                ],
                userId: null, // System action
                userName: 'Midtrans Webhook'
            );

            // Send notification to customer
            if ($internalStatus === 'paid') {
                try {
                    $order->user->notify(new \App\Notifications\OrderStatusUpdatedNotification($order));
                } catch (\Throwable $e) {
                    Log::error('Failed to send payment success notification', [
                        'order_id' => $order->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }

    /**
     * Update order status based on payment status
     *
     * @param Order $order
     * @param string $paymentStatus
     * @return void
     */
    protected function updateOrderStatus(Order $order, string $paymentStatus): void
    {
        $orderStatus = match ($paymentStatus) {
            'paid' => 'paid',
            'failed' => 'cancelled',
            'refunded' => 'refunded',
            'partial_refunded' => 'refunded',
            default => $order->status,
        };

        // Only update if status is different
        if ($order->status !== $orderStatus) {
            $oldStatus = $order->status;
            $order->update(['status' => $orderStatus]);

            // Log order status change
            AuditLogService::log(
                action: 'order_status_updated',
                targetType: 'Order',
                targetId: $order->id,
                payload: [
                    'order_number' => $order->order_number,
                    'old_status' => $oldStatus,
                    'new_status' => $orderStatus,
                    'trigger' => 'payment_webhook',
                ],
                userId: null,
                userName: 'Midtrans Webhook'
            );

            // Restore stock if order is cancelled/failed
            if (in_array($orderStatus, ['cancelled', 'refunded'])) {
                $this->restoreOrderStock($order);
            }
        }
    }

    /**
     * Restore stock when order is cancelled or refunded
     *
     * @param Order $order
     * @return void
     */
    protected function restoreOrderStock(Order $order): void
    {
        foreach ($order->items as $item) {
            try {
                $variant = $item->variant;
                $variant->increment('stock', $item->quantity);

                // Record inventory movement
                \App\Services\InventoryService::recordMovement(
                    variant: $variant,
                    quantity: $item->quantity,
                    type: 'return',
                    order: $order,
                    user: null,
                    notes: 'Stock restored due to order ' . $order->status
                );
            } catch (\Throwable $e) {
                Log::error('Failed to restore stock for cancelled order', [
                    'order_id' => $order->id,
                    'variant_id' => $item->product_variant_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}