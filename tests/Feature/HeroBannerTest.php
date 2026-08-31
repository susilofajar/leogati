<?php

namespace Tests\Feature;

use App\Models\HeroBanner;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroBannerTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['display_name' => 'Super Admin', 'description' => 'Akses Penuh']
        );

        $customerRole = Role::firstOrCreate(
            ['name' => 'customer'],
            ['display_name' => 'Pelanggan', 'description' => 'Pelanggan Toko']
        );

        $this->superAdmin = User::factory()->create();
        $this->superAdmin->roles()->attach($superAdminRole);

        $this->customer = User::factory()->create();
        $this->customer->roles()->attach($customerRole);
    }

    public function test_super_admin_can_view_hero_banners_index(): void
    {
        HeroBanner::create([
            'title'      => 'Test Banner 1',
            'image_path' => 'images/hero/test.jpg',
            'is_active'  => true,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.banner-hero.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Banner 1');
    }

    public function test_customer_cannot_access_hero_banners_admin(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('admin.banner-hero.index'));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_hero_banner(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('hero_banner.jpg', 1920, 1080);

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.banner-hero.store'), [
                'title'       => 'Promo Komponen Gaming',
                'subtitle'    => 'Diskon menarik untuk motherboard & GPU',
                'badge_text'  => 'Promo Terbatas',
                'image'       => $file,
                'button_text' => 'Lihat Katalog',
                'button_url'  => '/produk',
                'sort_order'  => 1,
                'is_active'   => '1',
            ]);

        $response->assertRedirect(route('admin.banner-hero.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('hero_banners', [
            'title'      => 'Promo Komponen Gaming',
            'badge_text' => 'Promo Terbatas',
            'sort_order' => 1,
            'is_active'  => true,
        ]);
    }

    public function test_super_admin_can_toggle_hero_banner_status(): void
    {
        $banner = HeroBanner::create([
            'title'      => 'Banner Toggle',
            'image_path' => 'images/hero/test.jpg',
            'is_active'  => true,
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.banner-hero.toggle', $banner->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('hero_banners', [
            'id'        => $banner->id,
            'is_active' => false,
        ]);
    }

    public function test_storefront_displays_active_hero_banners(): void
    {
        HeroBanner::create([
            'title'      => 'Banner Storefront Tampil',
            'subtitle'   => 'Deskripsi banner storefront',
            'badge_text' => 'Eksklusif',
            'image_path' => 'images/hero/test.jpg',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Banner Storefront Tampil');
    }
}
