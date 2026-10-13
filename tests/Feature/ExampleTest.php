<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\PatcheeDataSeeder;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PatcheeDataSeeder::class);
    }

    public function test_the_homepage_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('PATCHEE');
    }

    public function test_collections_page_returns_successful_response(): void
    {
        $response = $this->get('/collections/all');
        $response->assertStatus(200);
    }

    public function test_cart_data_returns_json(): void
    {
        $response = $this->get('/cart/data');
        $response->assertStatus(200);
        $response->assertJsonStructure(['items', 'count', 'subtotal', 'freeShippingThreshold']);
    }

    public function test_track_order_page_returns_successful_response(): void
    {
        $response = $this->get('/track-order');
        $response->assertStatus(200);
        $response->assertSee('TRACK YOUR ORDER');
    }

    public function test_admin_login_page_returns_successful_response(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('ADMINISTRATION PORTAL');
    }
}
