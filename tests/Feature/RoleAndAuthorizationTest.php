<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    /**
     * Test guest cannot access admin dashboard.
     */
    public function test_guest_is_redirected_when_accessing_admin_dashboard(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect(route('login'));
    }

    /**
     * Test regular customer receives 403 when accessing admin dashboard.
     */
    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $customer = User::where('email', 'pelanggan@example.com')->first();

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertStatus(403);
    }

    /**
     * Test super admin can access admin dashboard.
     */
    public function test_super_admin_can_access_admin_dashboard(): void
    {
        $superAdmin = User::where('email', 'superadmin@leogati.store')->first();

        $response = $this->actingAs($superAdmin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Operasional');
        $response->assertSee('Super Administrator');
    }

    /**
     * Test admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('email', 'admin@leogati.store')->first();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Operasional');
    }
}
