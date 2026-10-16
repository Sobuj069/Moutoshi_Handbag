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

    public function test_admin_can_create_product_with_multiple_gallery_images(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();
        $category = \App\Models\Category::first();

        \Illuminate\Support\Facades\Storage::fake('public');

        $primary = \Illuminate\Http\UploadedFile::fake()->image('primary.jpg', 600, 600);
        $gallery1 = \Illuminate\Http\UploadedFile::fake()->image('gallery1.jpg', 800, 800);
        $gallery2 = \Illuminate\Http\UploadedFile::fake()->image('gallery2.jpg', 800, 800);

        $response = $this->actingAs($admin)->post('/admin/products', [
            'title' => 'Test Multi-Image Handbag',
            'category_id' => $category->id,
            'price' => 1200,
            'stock' => 20,
            'image_file' => $primary,
            'gallery_images' => [$gallery1, $gallery2],
            'is_active' => 1,
        ]);

        $response->assertRedirect('/admin/products');

        $product = \App\Models\Product::where('title', 'Test Multi-Image Handbag')->first();
        $this->assertNotNull($product);
        $this->assertNotEmpty($product->primary_image);
        $this->assertCount(2, $product->images);

        // Test delete gallery image via route
        $imageToDelete = $product->images->first();
        $delResponse = $this->actingAs($admin)->deleteJson('/admin/products/gallery/' . $imageToDelete->id);
        $delResponse->assertStatus(200);
        $delResponse->assertJson(['success' => true]);

        $this->assertDatabaseMissing('product_images', ['id' => $imageToDelete->id]);
        $this->assertCount(1, $product->fresh()->images);
    }
}
