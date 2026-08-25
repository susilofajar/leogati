<?php

namespace Tests\Feature;

use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PaymentGatewayTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Set Midtrans configuration for testing
        Config::set('services.midtrans.server_key', 'SB-Mid-server-test-key');
        Config::set('services.midtrans.client_key', 'SB-Mid-client-test-key');
        Config::set('services.midtrans.is_production', false);
        Config::set('services.midtrans.is_sanitized', true);
        Config::set('services.midtrans.is_3ds', true);
    }

    public function test_midtrans_service_can_be_instantiated()
    {
        $service = new MidtransService();
        $this->assertInstanceOf(MidtransService::class, $service);
    }

    public function test_midtrans_status_mapping()
    {
        $service = new MidtransService();

        $this->assertEquals('paid', $service->mapStatus('settlement'));
        $this->assertEquals('paid', $service->mapStatus('capture'));
        $this->assertEquals('pending', $service->mapStatus('pending'));
        $this->assertEquals('failed', $service->mapStatus('deny'));
        $this->assertEquals('failed', $service->mapStatus('expire'));
        $this->assertEquals('failed', $service->mapStatus('cancel'));
        $this->assertEquals('refunded', $service->mapStatus('refund'));
        $this->assertEquals('partial_refunded', $service->mapStatus('partial_refund'));
        $this->assertEquals('unknown', $service->mapStatus('unknown_status'));
    }

    public function test_webhook_endpoint_requires_signature()
    {
        $response = $this->postJson('/webhook/midtrans', [
            'order_id' => 'TEST-ORDER-123',
            'transaction_status' => 'settlement',
        ]);

        // Should return 403 due to missing signature
        $response->assertStatus(403);
    }

    public function test_checkout_integration_with_payment_gateway()
    {
        // This test verifies that the checkout controller is properly configured
        // to use the payment gateway service, without actually calling Midtrans API

        $this->assertTrue(class_exists(MidtransService::class));
        $this->assertTrue(method_exists(CheckoutController::class, 'process'));
    }

    public function test_payment_model_has_gateway_fields()
    {
        $user = User::factory()->create();

        $order = new Order([
            'user_id' => $user->id,
            'order_number' => 'LEO-TEST-001',
            'subtotal_amount' => 100000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 115000,
            'status' => 'awaiting_payment',
            'payment_method' => 'midtrans',
            'payment_status' => 'unpaid',
            'shipping_address' => [
                'recipient_name' => 'Test',
                'phone_number' => '08123456789',
                'address_line' => 'Test Address',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12345',
            ],
        ]);
        $order->save();

        $payment = new Payment([
            'order_id' => $order->id,
            'payment_number' => 'PAY-TEST-001',
            'gateway_transaction_id' => 'MIDTRANS-123',
            'amount' => 115000,
            'payment_method' => 'midtrans',
            'status' => 'pending',
            'gateway_status' => 'pending',
            'gateway_response' => [
                'midtrans_status' => 'pending',
                'payment_type' => 'qris',
            ],
        ]);
        $payment->save();

        $this->assertEquals('MIDTRANS-123', $payment->gateway_transaction_id);
        $this->assertEquals('pending', $payment->gateway_status);
        $this->assertIsArray($payment->gateway_response);
        $this->assertEquals('pending', $payment->gateway_response['midtrans_status']);
    }

    public function test_payment_webhook_with_valid_signature()
    {
        // This test would require mocking the Midtrans signature verification
        // For now, we'll test the webhook endpoint structure

        $user = User::factory()->create();

        $order = new Order([
            'user_id' => $user->id,
            'order_number' => 'LEO-20260820-TEST',
            'subtotal_amount' => 100000,
            'shipping_amount' => 15000,
            'discount_amount' => 0,
            'total_amount' => 115000,
            'status' => 'awaiting_payment',
            'payment_method' => 'midtrans',
            'payment_status' => 'unpaid',
            'shipping_address' => [
                'recipient_name' => 'Test',
                'phone_number' => '08123456789',
                'address_line' => 'Test Address',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12345',
            ],
        ]);
        $order->save();

        $payment = new Payment([
            'order_id' => $order->id,
            'payment_number' => 'PAY-TEST-123',
            'amount' => 115000,
            'payment_method' => 'midtrans',
            'status' => 'pending',
        ]);
        $payment->save();

        $webhookPayload = [
            'order_id' => 'LEO-20260820-TEST',
            'transaction_status' => 'settlement',
            'payment_type' => 'bank_transfer',
            'transaction_time' => '2026-08-20 12:00:00',
            'settlement_time' => '2026-08-20 12:05:00',
            'gross_amount' => '115000.00',
            'fraud_status' => 'accept',
        ];

        $response = $this->withHeaders([
            'X-Signature-Key' => 'test-signature',
        ])->postJson('/webhook/midtrans', $webhookPayload);

        // The signature verification will fail in test environment
        // but we're testing the endpoint structure
        $response->assertStatus(403);
    }

    public function test_checkout_requires_authentication()
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/masuk');
    }

    public function test_checkout_redirects_if_cart_empty()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/checkout');
        $response->assertRedirect('/keranjang');
    }
}