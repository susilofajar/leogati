<?php

namespace Tests\Feature;

use App\Services\ShippingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_shipping_service_can_be_instantiated()
    {
        $service = new ShippingService();
        $this->assertInstanceOf(ShippingService::class, $service);
    }

    public function test_get_available_couriers()
    {
        $service = new ShippingService();
        $couriers = $service->getAvailableCouriers();

        $this->assertIsArray($couriers);
        $this->assertArrayHasKey('jne', $couriers);
        $this->assertArrayHasKey('jnt', $couriers);
        $this->assertArrayHasKey('sicepat', $couriers);
        $this->assertEquals('JNE', $couriers['jne']['name']);
        $this->assertEquals('J&T Express', $couriers['jnt']['name']);
        $this->assertEquals('SiCepat', $couriers['sicepat']['name']);
    }

    public function test_get_available_services_for_jne()
    {
        $service = new ShippingService();
        $services = $service->getAvailableServices('jne');

        $this->assertIsArray($services);
        $this->assertArrayHasKey('JNE REG', $services);
        $this->assertArrayHasKey('JNE YES', $services);
        $this->assertArrayHasKey('JNE OKE', $services);
        $this->assertEquals('JNE Regular', $services['JNE REG']['name']);
    }

    public function test_get_available_services_for_jnt()
    {
        $service = new ShippingService();
        $services = $service->getAvailableServices('jnt');

        $this->assertIsArray($services);
        $this->assertArrayHasKey('J&T EZ', $services);
        $this->assertArrayHasKey('J&T Express', $services);
    }

    public function test_get_available_services_for_sicepat()
    {
        $service = new ShippingService();
        $services = $service->getAvailableServices('sicepat');

        $this->assertIsArray($services);
        $this->assertArrayHasKey('SiCepat BEST', $services);
        $this->assertArrayHasKey('SiCepat REG', $services);
    }

    public function test_calculate_shipping_cost_jne()
    {
        $service = new ShippingService();

        $origin = ['city' => 'Jakarta Pusat', 'district' => 'Gambir'];
        $destination = ['city' => 'Surabaya', 'district' => 'Surabaya Pusat'];
        $weightGrams = 1000; // 1 kg

        $result = $service->calculateShippingCost($origin, $destination, $weightGrams, 'jne');

        $this->assertTrue($result['success']);
        $this->assertEquals('jne', $result['courier']);
        $this->assertIsArray($result['services']);
        $this->assertNotEmpty($result['services']);
    }

    public function test_calculate_shipping_cost_jnt()
    {
        $service = new ShippingService();

        $origin = ['city' => 'Jakarta Pusat', 'district' => 'Gambir'];
        $destination = ['city' => 'Bandung', 'district' => 'Bandung Pusat'];
        $weightGrams = 2000; // 2 kg

        $result = $service->calculateShippingCost($origin, $destination, $weightGrams, 'jnt');

        $this->assertTrue($result['success']);
        $this->assertEquals('jnt', $result['courier']);
        $this->assertIsArray($result['services']);
    }

    public function test_calculate_shipping_cost_sicepat()
    {
        $service = new ShippingService();

        $origin = ['city' => 'Jakarta Pusat', 'district' => 'Gambir'];
        $destination = ['city' => 'Medan', 'district' => 'Medan Pusat'];
        $weightGrams = 500; // 0.5 kg (will be rounded to 1 kg)

        $result = $service->calculateShippingCost($origin, $destination, $weightGrams, 'sicepat');

        $this->assertTrue($result['success']);
        $this->assertEquals('sicepat', $result['courier']);
        $this->assertIsArray($result['services']);
    }

    public function test_track_shipment_jne()
    {
        $service = new ShippingService();

        $trackingNumber = 'JP1234567890';
        $result = $service->trackShipment($trackingNumber, 'jne');

        $this->assertTrue($result['success']);
        $this->assertEquals($trackingNumber, $result['tracking_number']);
        $this->assertEquals('jne', $result['courier']);
        $this->assertIsArray($result['history']);
        $this->assertNotEmpty($result['history']);
    }

    public function test_track_shipment_jnt()
    {
        $service = new ShippingService();

        $trackingNumber = 'JT9876543210';
        $result = $service->trackShipment($trackingNumber, 'jnt');

        $this->assertTrue($result['success']);
        $this->assertEquals($trackingNumber, $result['tracking_number']);
        $this->assertIsArray($result['history']);
    }

    public function test_track_shipment_sicepat()
    {
        $service = new ShippingService();

        $trackingNumber = 'SC1122334455';
        $result = $service->trackShipment($trackingNumber, 'sicepat');

        $this->assertTrue($result['success']);
        $this->assertEquals($trackingNumber, $result['tracking_number']);
        $this->assertIsArray($result['history']);
    }

    public function test_format_tracking_status()
    {
        $service = new ShippingService();

        $this->assertEquals('Diambil Kurir', $service->formatTrackingStatus('picked_up'));
        $this->assertEquals('Dalam Perjalanan', $service->formatTrackingStatus('in_transit'));
        $this->assertEquals('Terkirim', $service->formatTrackingStatus('delivered'));
        $this->assertEquals('Gagal Kirim', $service->formatTrackingStatus('failed'));
        $this->assertEquals('Dikembalikan', $service->formatTrackingStatus('returned'));
        $this->assertEquals('Status Tidak Diketahui', $service->formatTrackingStatus('unknown'));
    }

    public function test_shipping_api_couriers_endpoint()
    {
        $response = $this->get('/api/shipping/couriers');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'couriers' => [
                'jne',
                'jnt',
                'sicepat',
            ],
        ]);
    }

    public function test_shipping_api_services_endpoint()
    {
        $response = $this->get('/api/shipping/services/jne');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'courier' => 'jne',
        ]);
        $response->assertJsonStructure([
            'success',
            'courier',
            'services',
        ]);
    }

    public function test_shipping_api_calculate_endpoint()
    {
        $response = $this->post('/api/shipping/calculate', [
            'origin_city' => 'Jakarta Pusat',
            'origin_district' => 'Gambir',
            'destination_city' => 'Surabaya',
            'destination_district' => 'Surabaya Pusat',
            'weight_grams' => 1000,
            'courier' => 'jne',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'courier' => 'jne',
        ]);
    }

    public function test_shipping_api_track_endpoint()
    {
        $response = $this->post('/api/shipping/track', [
            'tracking_number' => 'JP1234567890',
            'courier' => 'jne',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'tracking_number' => 'JP1234567890',
            'courier' => 'jne',
        ]);
    }

    public function test_shipping_calculate_with_invalid_courier()
    {
        $service = new ShippingService();

        $origin = ['city' => 'Jakarta Pusat', 'district' => 'Gambir'];
        $destination = ['city' => 'Surabaya', 'district' => 'Surabaya Pusat'];
        $weightGrams = 1000;

        $result = $service->calculateShippingCost($origin, $destination, $weightGrams, 'invalid_courier');

        // Should still succeed with fallback calculation
        $this->assertTrue($result['success']);
    }

    public function test_shipping_services_with_invalid_courier()
    {
        $service = new ShippingService();
        $services = $service->getAvailableServices('invalid_courier');

        $this->assertIsArray($services);
        $this->assertEmpty($services);
    }
}