<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementExtraTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $superAdmin;
    protected User $customer;
    protected Role $customerRole;
    protected Role $adminRole;
    protected Role $superAdminRole;
    protected Role $warehouseRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdminRole = Role::create(['name' => 'super_admin', 'display_name' => 'Super Administrator']);
        $this->adminRole = Role::create(['name' => 'admin', 'display_name' => 'Administrator']);
        $this->warehouseRole = Role::create(['name' => 'warehouse_staff', 'display_name' => 'Staf Gudang']);
        $this->customerRole = Role::create(['name' => 'customer', 'display_name' => 'Pelanggan']);

        $this->superAdmin = User::factory()->create(['name' => 'Super Admin', 'email' => 'super@example.com']);
        $this->superAdmin->roles()->attach($this->superAdminRole);

        $this->admin = User::factory()->create(['name' => 'Admin User', 'email' => 'admin@example.com']);
        $this->admin->roles()->attach($this->adminRole);

        $this->customer = User::factory()->create(['name' => 'Customer', 'email' => 'customer@example.com']);
        $this->customer->roles()->attach($this->customerRole);
    }

    // --- CATEGORY TESTS ---

    public function test_customer_cannot_access_admin_categories(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.kategori.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_category_list(): void
    {
        Category::create(['name' => 'Laptop Gaming', 'slug' => 'laptop-gaming']);

        $response = $this->actingAs($this->admin)->get(route('admin.kategori.index'));
        $response->assertStatus(200);
        $response->assertSee('Laptop Gaming');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.kategori.store'), [
            'name'        => 'Monitor Gaming',
            'slug'        => 'monitor-gaming',
            'description' => 'Kategori monitor refresh rate tinggi.',
            'sort_order'  => 1,
            'is_active'   => '1',
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Monitor Gaming',
            'slug' => 'monitor-gaming',
        ]);
    }

    public function test_admin_can_update_category(): void
    {
        $cat = Category::create(['name' => 'Motherboard', 'slug' => 'motherboard']);

        $response = $this->actingAs($this->admin)->put(route('admin.kategori.update', $cat->id), [
            'name'       => 'Motherboard Gaming & Server',
            'sort_order' => 5,
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('categories', [
            'id'   => $cat->id,
            'name' => 'Motherboard Gaming & Server',
        ]);
    }

    public function test_admin_cannot_delete_category_with_existing_products(): void
    {
        $cat = Category::create(['name' => 'Laptop', 'slug' => 'laptop']);
        $brand = Brand::create(['name' => 'ASUS', 'slug' => 'asus']);

        Product::create([
            'category_id'            => $cat->id,
            'brand_id'               => $brand->id,
            'name'                   => 'ASUS Zenbook',
            'slug'                   => 'asus-zenbook',
            'warranty_period_months' => 24,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.kategori.destroy', $cat->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $cat->id]);
    }

    // --- BRAND TESTS ---

    public function test_admin_can_create_and_manage_brand(): void
    {
        // 1. Create
        $response = $this->actingAs($this->admin)->post(route('admin.merek.store'), [
            'name'        => 'Corsair',
            'slug'        => 'corsair',
            'description' => 'Produsen RAM dan PSU ternama.',
            'is_active'   => '1',
        ]);

        $response->assertRedirect(route('admin.merek.index'));
        $this->assertDatabaseHas('brands', ['name' => 'Corsair']);

        $brand = Brand::where('name', 'Corsair')->first();

        // 2. Update
        $updateResponse = $this->actingAs($this->admin)->put(route('admin.merek.update', $brand->id), [
            'name'        => 'Corsair Gaming',
            'slug'        => 'corsair-gaming',
            'description' => 'Updated desc',
        ]);

        $updateResponse->assertRedirect(route('admin.merek.index'));
        $this->assertDatabaseHas('brands', ['name' => 'Corsair Gaming']);

        // 3. Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.merek.destroy', $brand->id));
        $deleteResponse->assertRedirect(route('admin.merek.index'));
        $this->assertDatabaseMissing('brands', ['id' => $brand->id]);
    }

    public function test_super_admin_can_upload_and_update_brand_logo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $logo = \Illuminate\Http\UploadedFile::fake()->image('razer-logo.png');

        $response = $this->actingAs($this->superAdmin)->post(route('admin.merek.store'), [
            'name' => 'Razer Gaming',
            'slug' => 'razer-gaming',
            'logo' => $logo,
        ]);

        $response->assertRedirect(route('admin.merek.index'));
        $brand = Brand::where('slug', 'razer-gaming')->first();
        $this->assertNotNull($brand);
        $this->assertNotNull($brand->logo);
    }

    public function test_regular_admin_cannot_upload_brand_logo(): void
    {
        \Illuminate\Support\Facades\Storage::fake('public');
        $logo = \Illuminate\Http\UploadedFile::fake()->image('fake-logo.png');

        $response = $this->actingAs($this->admin)->post(route('admin.merek.store'), [
            'name' => 'Fake Brand',
            'slug' => 'fake-brand',
            'logo' => $logo,
        ]);

        $response->assertSessionHasErrors('logo');
    }

    // --- USER & RBAC MANAGEMENT TESTS ---

    public function test_admin_can_view_users_list(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengguna.index'));
        $response->assertStatus(200);
        $response->assertSee('Super Admin');
        $response->assertSee('Admin User');
        $response->assertSee('Customer');
    }

    public function test_admin_can_create_staff_user_with_roles(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.pengguna.store'), [
            'name'     => 'Staf Gudang Baru',
            'email'    => 'staf.gudang@example.com',
            'password' => 'secret1234',
            'roles'    => [$this->warehouseRole->id],
        ]);

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseHas('users', [
            'name'  => 'Staf Gudang Baru',
            'email' => 'staf.gudang@example.com',
        ]);

        $newUser = User::where('email', 'staf.gudang@example.com')->first();
        $this->assertTrue($newUser->hasRole('warehouse_staff'));
    }

    public function test_admin_can_update_user_roles(): void
    {
        $targetUser = User::factory()->create(['name' => 'Target User', 'email' => 'target@example.com']);
        $targetUser->roles()->attach($this->customerRole);

        $response = $this->actingAs($this->admin)->put(route('admin.pengguna.update', $targetUser->id), [
            'name'  => 'Target Promoted',
            'email' => 'target@example.com',
            'roles' => [$this->adminRole->id],
        ]);

        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertTrue($targetUser->fresh()->hasRole('admin'));
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('admin.pengguna.destroy', $this->admin->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_admin_can_delete_other_user(): void
    {
        $targetUser = User::factory()->create(['name' => 'Delete Me', 'email' => 'deleteme@example.com']);
        $targetUser->roles()->attach($this->customerRole);

        $response = $this->actingAs($this->admin)->delete(route('admin.pengguna.destroy', $targetUser->id));
        $response->assertRedirect(route('admin.pengguna.index'));
        $this->assertDatabaseMissing('users', ['id' => $targetUser->id]);
    }
}
