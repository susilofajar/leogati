<?php

namespace App\Services;

use App\Services\Contracts\PaymentGatewayInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService implements PaymentGatewayInterface
{
    public function __construct()
    {
        $this->configureMidtrans();
    }

    /**
     * Configure Midtrans with environment variables
     */
    protected function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$clientKey = config('services.midtrans.client_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = config('services.midtrans.is_sanitized', true);
        Config::$is3ds = config('services.midtrans.is_3ds', true);
    }

    /**
     * Create payment transaction and return payment URL or token
     *
     * @param array $paymentData
     * @return array
     */
    public function createPayment(array $paymentData): array
    {
        try {
            $transactionDetails = [
                'order_id' => $paymentData['order_id'],
                'gross_amount' => (int) $paymentData['amount'],
            ];

            $customerDetails = [
                'first_name' => $paymentData['customer_name'] ?? 'Customer',
                'email' => $paymentData['customer_email'] ?? 'customer@example.com',
                'phone' => $paymentData['customer_phone'] ?? '',
            ];

            $itemDetails = [];
            if (isset($paymentData['items']) && is_array($paymentData['items'])) {
                foreach ($paymentData['items'] as $item) {
                    $itemDetails[] = [
                        'id' => $item['id'],
                        'price' => (int) $item['price'],
                        'quantity' => (int) $item['quantity'],
                        'name' => $item['name'],
                    ];
                }
            }

            $enabledPayments = $paymentData['enabled_payments'] ?? [
                'credit_card',
                'gopay',
                'shopeepay',
                'bca_va',
                'bni_va',
                'bri_va',
                'mandiri_va',
                'permata_va',
                'qris',
            ];

            $transactionData = [
                'transaction_details' => $transactionDetails,
                'customer_details' => $customerDetails,
                'item_details' => $itemDetails,
                'enabled_payments' => $enabledPayments,
            ];

            // Add custom fields if provided
            if (isset($paymentData['custom_field1'])) {
                $transactionData['custom_field1'] = $paymentData['custom_field1'];
            }
            if (isset($paymentData['custom_field2'])) {
                $transactionData['custom_field2'] = $paymentData['custom_field2'];
            }
            if (isset($paymentData['custom_field3'])) {
                $transactionData['custom_field3'] = $paymentData['custom_field3'];
            }

            $snapToken = Snap::getSnapToken($transactionData);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'redirect_url' => config('services.midtrans.snap_url') . $snapToken,
                'transaction_id' => $paymentData['order_id'],
            ];

        } catch (Exception $e) {
            Log::error('Midtrans Payment Creation Error: ' . $e->getMessage(), [
                'order_id' => $paymentData['order_id'] ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment status from payment gateway
     *
     * @param string $transactionId
     * @return array
     */
    public function getPaymentStatus(string $transactionId): array
    {
        try {
            $status = Transaction::status($transactionId);

            return [
                'success' => true,
                'status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? null,
                'transaction_time' => $status->transaction_time ?? null,
                'settlement_time' => $status->settlement_time ?? null,
                'gross_amount' => $status->gross_amount ?? null,
                'fraud_status' => $status->fraud_status ?? null,
                'status_message' => $status->status_message ?? null,
            ];

        } catch (Exception $e) {
            Log::error('Midtrans Status Check Error: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Cancel payment transaction
     *
     * @param string $transactionId
     * @return array
     */
    public function cancelPayment(string $transactionId): array
    {
        try {
            $result = Transaction::cancel($transactionId);

            return [
                'success' => true,
                'status' => $result->transaction_status ?? 'cancelled',
                'message' => 'Transaction cancelled successfully',
            ];

        } catch (Exception $e) {
            Log::error('Midtrans Cancel Error: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process refund
     *
     * @param string $transactionId
     * @param float $amount
     * @param string $reason
     * @return array
     */
    public function refundPayment(string $transactionId, float $amount, string $reason): array
    {
        try {
            $refundData = [
                'amount' => (int) $amount,
                'reason' => $reason,
            ];

            $result = Transaction::refund($transactionId, $refundData);

            return [
                'success' => true,
                'status' => $result->transaction_status ?? 'refunded',
                'message' => 'Refund processed successfully',
            ];

        } catch (Exception $e) {
            Log::error('Midtrans Refund Error: ' . $e->getMessage(), [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'reason' => $reason,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify webhook signature
     *
     * @param array $payload
     * @param string $signature
     * @return bool
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        try {
            $notification = new Notification();
            return $notification->verifySignatureKey($signature);
        } catch (Exception $e) {
            Log::error('Midtrans Webhook Signature Verification Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Parse webhook notification
     *
     * @param array $payload
     * @return array
     */
    public function parseWebhookNotification(array $payload): array
    {
        try {
            $notification = new Notification();

            return [
                'success' => true,
                'order_id' => $notification->order_id,
                'status' => $notification->transaction_status,
                'payment_type' => $notification->payment_type,
                'transaction_time' => $notification->transaction_time,
                'settlement_time' => $notification->settlement_time,
                'gross_amount' => $notification->gross_amount,
                'fraud_status' => $notification->fraud_status,
                'payment_code' => $notification->payment_code ?? null,
                'va_number' => $notification->va_numbers[0]->va_number ?? null,
                'bank' => $notification->va_numbers[0]->bank ?? null,
                'raw_data' => $notification,
            ];

        } catch (Exception $e) {
            Log::error('Midtrans Webhook Parsing Error: ' . $e->getMessage(), [
                'payload' => $payload,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Map Midtrans status to internal payment status
     *
     * @param string $midtransStatus
     * @return string
     */
    public function mapStatus(string $midtransStatus): string
    {
        return match ($midtransStatus) {
            'capture', 'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'expire', 'cancel' => 'failed',
            'refund' => 'refunded',
            'partial_refund' => 'partial_refunded',
            default => 'unknown',
        };
    }
}