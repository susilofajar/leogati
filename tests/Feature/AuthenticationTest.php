<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    /**
     * Test login screen can be rendered.
     */
    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/masuk');

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun Anda');
    }

    /**
     * Test register screen can be rendered.
     */
    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/daftar');

        $response->assertStatus(200);
        $response->assertSee('Buat Akun LEOGATISTORE');
    }

    /**
     * Test user can register and is assigned customer role.
     */
    public function test_user_can_register(): void
    {
        $response = $this->post('/daftar', [
            'name' => 'Ahmad Dani',
            'email' => 'ahmad@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('customer.dashboard'));

        $user = User::where('email', 'ahmad@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('customer'));
    }

    /**
     * Test user can login with valid credentials.
     */
    public function test_user_can_login(): void
    {
        $user = User::where('email', 'pelanggan@example.com')->first();

        $response = $this->post('/masuk', [
            'email' => 'pelanggan@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('customer.dashboard'));
    }

    /**
     * Test user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/masuk', [
            'email' => 'pelanggan@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Test user can logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::where('email', 'pelanggan@example.com')->first();

        $response = $this->actingAs($user)->post('/keluar');

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }
}
