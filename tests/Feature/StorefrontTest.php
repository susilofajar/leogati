<?php

namespace Tests\Feature;

use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleAndPermissionSeeder::class,
            CatalogBaseSeeder::class,
        ]);
    }

    /**
     * Test storefront home page can be rendered.
     */
    public function test_home_page_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('LEOGATISTORE');
        $response->assertSee('Laptop');
        $response->assertSee('Simulasi PC Builder');
        $response->assertSee('Cek Garansi Resmi');
    }

    /**
     * Test warranty check page can be rendered and searched.
     */
    public function test_warranty_check_page_can_be_rendered(): void
    {
        $response = $this->get('/garansi/cek');

        $response->assertSee('Pengecekan Status', false);
        $response->assertSee('Masa Berlaku Garansi', false);

        $searchResponse = $this->get('/garansi/cek?sn=SN-TEST-12345');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('SN-TEST-12345');
    }

    /**
     * Test PC builder page can be rendered.
     */
    public function test_pc_builder_page_can_be_rendered(): void
    {
        $response = $this->get('/pc-builder');

        $response->assertStatus(200);
        $response->assertSee('Simulasi Rakit PC');
        $response->assertSee('Kompatibilitas');
    }
}
