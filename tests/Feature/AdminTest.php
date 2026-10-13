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

    public function test_admin_can_filter_products_by_section(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();

        // Filter featured
        $response = $this->actingAs($admin)->get('/admin/products?section=featured');
        $response->assertStatus(200);
        $response->assertSee('Featured on Homepage');

        // Filter sale
        $response = $this->actingAs($admin)->get('/admin/products?section=sale');
        $response->assertStatus(200);

        // Filter new arrival
        $response = $this->actingAs($admin)->get('/admin/products?section=new');
        $response->assertStatus(200);
    }

    public function test_admin_can_bulk_action_products(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();
        $products = \App\Models\Product::take(2)->get();
        $ids = $products->pluck('id')->toArray();

        // Bulk mark as featured
        $response = $this->actingAs($admin)->post('/admin/products/bulk-action', [
            'action' => 'mark_featured',
            'product_ids' => $ids,
        ]);
        $response->assertRedirect();
        foreach ($ids as $id) {
            $this->assertTrue(\App\Models\Product::find($id)->is_featured);
        }

        // Bulk set draft
        $response = $this->actingAs($admin)->post('/admin/products/bulk-action', [
            'action' => 'set_draft',
            'product_ids' => $ids,
        ]);
        $response->assertRedirect();
        foreach ($ids as $id) {
            $this->assertFalse(\App\Models\Product::find($id)->is_active);
        }
    }
}
