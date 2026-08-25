<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SavedPcBuild;
use App\Models\User;
use Database\Seeders\CatalogBaseSeeder;
use Database\Seeders\ProductCatalogSeeder;
use Database\Seeders\RoleAndPermissionSeeder;
use Database\Seeders\WarehouseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PcBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RoleAndPermissionSeeder::class,
            CatalogBaseSeeder::class,
            ProductCatalogSeeder::class,
            WarehouseSeeder::class,
        ]);

        $this->user = User::where('email', 'pelanggan@example.com')->first();
    }

    /**
     * Test PC Builder page can be rendered.
     */
    public function test_pc_builder_page_can_be_rendered(): void
    {
        $response = $this->get('/pc-builder');
        $response->assertStatus(200);
        $response->assertSee('Simulasi Rakit PC');
        $response->assertSee('Daftar Komponen Rakitan');
    }

    /**
     * Test compatibility engine detects compatible AMD AM5 + DDR5 setup.
     */
    public function test_compatibility_engine_detects_compatible_combination(): void
    {
        $cpuAm5 = ProductVariant::where('sku', 'AMD-RYZEN7-7800X3D-BOX')->first();
        $mbAm5  = ProductVariant::where('sku', 'ASUS-ROG-B650A-WIFI')->first();
        $ramDdr5 = ProductVariant::where('sku', 'CORSAIR-VEN-RGB-32G-6000')->first();
        $psu     = ProductVariant::where('sku', 'CORSAIR-RM850E-850W')->first();

        $response = $this->postJson('/pc-builder/validasi', [
            'components' => [
                'cpu'         => $cpuAm5->id,
                'motherboard' => $mbAm5->id,
                'ram'         => $ramDdr5->id,
                'psu'         => $psu->id,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'compatible',
        ]);
        $response->assertJsonFragment([
            'title' => 'Soket CPU & Motherboard Cocok',
        ]);
        $response->assertJsonFragment([
            'title' => 'Tipe RAM Sesuai',
        ]);
    }

    /**
     * Test compatibility engine detects socket mismatch (AM5 CPU vs LGA1700 Motherboard).
     */
    public function test_compatibility_engine_detects_socket_incompatibility(): void
    {
        $cpuAm5   = ProductVariant::where('sku', 'AMD-RYZEN7-7800X3D-BOX')->first();
        $mbIntel  = ProductVariant::where('sku', 'ASUS-TUF-Z790-WIFI')->first();

        $response = $this->postJson('/pc-builder/validasi', [
            'components' => [
                'cpu'         => $cpuAm5->id,
                'motherboard' => $mbIntel->id,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'incompatible',
        ]);
        $response->assertJsonFragment([
            'title' => 'Soket CPU & Motherboard Tidak Cocok',
        ]);
    }

    /**
     * Test compatibility engine detects RAM type mismatch (DDR5 Motherboard vs DDR4 RAM).
     */
    public function test_compatibility_engine_detects_ram_type_incompatibility(): void
    {
        $mbDdr5  = ProductVariant::where('sku', 'ASUS-ROG-B650A-WIFI')->first();
        $ramDdr4 = ProductVariant::where('sku', 'CORSAIR-LPX-16G-D4-3200')->first();

        $response = $this->postJson('/pc-builder/validasi', [
            'components' => [
                'motherboard' => $mbDdr5->id,
                'ram'         => $ramDdr4->id,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'incompatible',
        ]);
        $response->assertJsonFragment([
            'title' => 'Tipe Memori RAM Tidak Didukung',
        ]);
    }

    /**
     * Test saving a PC build generates a unique share token and loads correctly.
     */
    public function test_save_pc_build_generates_shareable_token(): void
    {
        $cpuAm5 = ProductVariant::where('sku', 'AMD-RYZEN7-7800X3D-BOX')->first();
        $mbAm5  = ProductVariant::where('sku', 'ASUS-ROG-B650A-WIFI')->first();

        $response = $this->actingAs($this->user)->postJson('/pc-builder/simpan', [
            'build_name' => 'PC Gaming Impian Budi',
            'components' => [
                'cpu'         => $cpuAm5->id,
                'motherboard' => $mbAm5->id,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'share_token',
            'share_url',
        ]);

        $token = $response->json('share_token');
        $this->assertDatabaseHas('saved_pc_builds', [
            'share_token' => $token,
            'build_name'  => 'PC Gaming Impian Budi',
        ]);

        // Load build via share token
        $responseShow = $this->get('/pc-builder?share=' . $token);
        $responseShow->assertStatus(200);
    }

    /**
     * Test adding compatible PC build to shopping cart atomically.
     */
    public function test_add_pc_build_to_cart_atomically(): void
    {
        $cpuAm5 = ProductVariant::where('sku', 'AMD-RYZEN7-7800X3D-BOX')->first();
        $mbAm5  = ProductVariant::where('sku', 'ASUS-ROG-B650A-WIFI')->first();
        $ramDdr5 = ProductVariant::where('sku', 'CORSAIR-VEN-RGB-32G-6000')->first();

        $response = $this->actingAs($this->user)->post('/pc-builder/tambah-keranjang', [
            'components' => [
                'cpu'         => $cpuAm5->id,
                'motherboard' => $mbAm5->id,
                'ram'         => $ramDdr5->id,
            ],
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $cpuAm5->id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $mbAm5->id,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'product_variant_id' => $ramDdr5->id,
        ]);
    }
}
