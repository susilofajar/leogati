<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Role;
use App\Models\SavedPcBuild;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name'         => 'customer',
            'display_name' => 'Pelanggan',
        ]);

        $this->customer = User::factory()->create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@example.com',
            'password' => Hash::make('password123'),
        ]);
        $this->customer->roles()->attach($role);
    }

    public function test_customer_can_render_profile_edit_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('customer.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Informasi Profil');
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@example.com');
    }

    public function test_customer_can_update_profile(): void
    {
        $response = $this->actingAs($this->customer)->put(route('customer.profile.update'), [
            'name'  => 'Budi Pratama',
            'email' => 'budi.new@example.com',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id'    => $this->customer->id,
            'name'  => 'Budi Pratama',
            'email' => 'budi.new@example.com',
        ]);
    }

    public function test_customer_can_change_password_with_valid_current_password(): void
    {
        $response = $this->actingAs($this->customer)->put(route('customer.profile.password'), [
            'current_password'      => 'password123',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('newpassword123', $this->customer->fresh()->password));
    }

    public function test_customer_cannot_change_password_with_invalid_current_password(): void
    {
        $response = $this->actingAs($this->customer)->put(route('customer.profile.password'), [
            'current_password'      => 'wrongpassword',
            'password'              => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('password123', $this->customer->fresh()->password));
    }

    public function test_customer_can_manage_addresses(): void
    {
        // 1. Render Address List
        $response = $this->actingAs($this->customer)->get(route('customer.addresses.index'));
        $response->assertStatus(200);
        $response->assertSee('Buku Alamat Pengiriman');

        // 2. Create Address
        $createResponse = $this->actingAs($this->customer)->post(route('customer.addresses.store'), [
            'recipient_name' => 'Budi Santoso',
            'phone_number'   => '081234567890',
            'address_line'   => 'Jl. Sudirman No. 12',
            'city'           => 'Jakarta Pusat',
            'province'       => 'DKI Jakarta',
            'postal_code'    => '10110',
        ]);

        $createResponse->assertSessionHas('success');
        $this->assertDatabaseHas('addresses', [
            'user_id'        => $this->customer->id,
            'recipient_name' => 'Budi Santoso',
            'is_primary'     => true, // first address is automatically primary
        ]);

        $address1 = Address::where('user_id', $this->customer->id)->first();

        // 3. Create Second Address
        $this->actingAs($this->customer)->post(route('customer.addresses.store'), [
            'recipient_name' => 'Kantor Budi',
            'phone_number'   => '081234567891',
            'address_line'   => 'Jl. Gatot Subroto No. 45',
            'city'           => 'Jakarta Selatan',
            'province'       => 'DKI Jakarta',
            'postal_code'    => '12930',
        ]);

        $address2 = Address::where('recipient_name', 'Kantor Budi')->first();
        $this->assertFalse($address2->is_primary);

        // 4. Set Second Address as Default
        $setDefaultResponse = $this->actingAs($this->customer)->post(route('customer.addresses.set_default', $address2->id));
        $setDefaultResponse->assertSessionHas('success');

        $this->assertTrue($address2->fresh()->is_primary);
        $this->assertFalse($address1->fresh()->is_primary);

        // 5. Delete Non-Primary Address
        $deleteResponse = $this->actingAs($this->customer)->delete(route('customer.addresses.destroy', $address1->id));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('addresses', ['id' => $address1->id]);
    }

    public function test_customer_can_view_and_delete_saved_pc_builds(): void
    {
        $build = SavedPcBuild::create([
            'share_token'          => 'PCB-TEST01',
            'user_id'              => $this->customer->id,
            'build_name'           => 'Custom Gaming Rig',
            'components'           => ['cpu' => ['name' => 'Ryzen 7 7800X3D', 'price' => 6500000]],
            'total_price'          => 6500000,
            'estimated_wattage'    => 350,
            'compatibility_status' => 'compatible',
        ]);

        // Index
        $response = $this->actingAs($this->customer)->get(route('customer.builds.index'));
        $response->assertStatus(200);
        $response->assertSee('Custom Gaming Rig');
        $response->assertSee('PCB-TEST01');

        // Show
        $showResponse = $this->actingAs($this->customer)->get(route('customer.builds.show', $build->share_token));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Custom Gaming Rig');
        $showResponse->assertSee('Ryzen 7 7800X3D');

        // Delete
        $deleteResponse = $this->actingAs($this->customer)->delete(route('customer.builds.destroy', $build->share_token));
        $deleteResponse->assertSessionHas('success');
        $this->assertDatabaseMissing('saved_pc_builds', ['id' => $build->id]);
    }
}
