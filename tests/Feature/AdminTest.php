<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\PatcheeDataSeeder;
use App\Models\User;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PatcheeDataSeeder::class);
    }

    public function test_admin_can_access_dashboard_and_crud_pages(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();
        $this->assertNotNull($admin);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Analytics');

        // Test products list
        $response = $this->actingAs($admin)->get('/admin/products');
        $response->assertStatus(200);

        // Test orders list
        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);

        // Test categories list
        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);

        // Test banners list
        $response = $this->actingAs($admin)->get('/admin/banners');
        $response->assertStatus(200);

        // Test settings
        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $customer = User::where('email', 'customer@thepatchee.com')->first();
        $this->assertNotNull($customer);

        $response = $this->actingAs($customer)->get('/admin');
        $response->assertRedirect('/admin/login');
    }
}
