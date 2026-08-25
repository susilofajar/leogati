<?php

namespace App\Services\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Create payment transaction and return payment URL or token
     *
     * @param array $paymentData
     * @return array
     */
    public function createPayment(array $paymentData): array;

    /**
     * Get payment status from payment gateway
     *
     * @param string $transactionId
     * @return array
     */
    public function getPaymentStatus(string $transactionId): array;

    /**
     * Cancel payment transaction
     *
     * @param string $transactionId
     * @return array
     */
    public function cancelPayment(string $transactionId): array;

    /**
     * Process refund
     *
     * @param string $transactionId
     * @param float $amount
     * @param string $reason
     * @return array
     */
    public function refundPayment(string $transactionId, float $amount, string $reason): array;

    /**
     * Verify webhook signature
     *
     * @param array $payload
     * @param string $signature
     * @return bool
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool;

    /**
     * Parse webhook notification
     *
     * @param array $payload
     * @return array
     */
    public function parseWebhookNotification(array $payload): array;
}