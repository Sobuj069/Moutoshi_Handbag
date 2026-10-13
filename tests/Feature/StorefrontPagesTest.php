<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\PatcheeDataSeeder;
use App\Models\Product;

class StorefrontPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PatcheeDataSeeder::class);
    }

    public function test_all_custom_pages_return_status_200(): void
    {
        $urls = [
            '/' => 'PATCHEE',
            '/collections/all' => 'ALL PRODUCTS',
            '/pages/about-us' => 'WHERE IT ALL BEGAN',
            '/pages/contact' => 'SEND US A MESSAGE',
            '/pages/exchange-return-policy' => 'RETURN POLICY',
            '/pages/faqs' => 'FREQUENTLY ASKED QUESTIONS',
            '/pages/privacy-policy' => 'PRIVACY POLICY',
            '/pages/terms-conditions' => 'TERMS',
            '/pages/b2b-queries' => 'CORPORATE GIFTING',
            '/pages/complaint-portal' => 'CUSTOMER ESCALATION DESK',
            '/pages/meet-our-founder-ceo' => 'LEADERSHIP',
            '/blogs/news-insights' => 'STYLE STORIES',
            '/customer/login' => 'SIGN IN',
            '/customer/register' => 'CREATE ACCOUNT',
            '/cart' => 'SHOPPING BAG',
            '/track-order' => 'TRACK YOUR ORDER',
            '/admin/login' => 'ADMINISTRATION PORTAL',
        ];

        foreach ($urls as $url => $expectedText) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertSee($expectedText);
        }
    }

    public function test_checkout_page_renders_with_items_in_cart(): void
    {
        $product = Product::first();
        $cart = [
            $product->id => [
                'cart_key' => $product->id . '_default',
                'product_id' => $product->id,
                'title' => $product->title,
                'name' => $product->title,
                'price' => (float) $product->price,
                'quantity' => 1,
                'image' => $product->primary_image_url,
                'variant' => 'Black'
            ]
        ];

        $response = $this->withSession(['cart' => $cart])->get('/checkout');
        $response->assertStatus(200);
        $response->assertSee('CHECKOUT');
        $response->assertSee('Cash on Delivery');
    }

    public function test_single_product_page_renders_successfully(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->get('/products/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_blog_post_page_renders_successfully(): void
    {
        $response = $this->get('/blogs/news-insights/how-to-style-structured-tote-bags');
        $response->assertStatus(200);
        $response->assertSee('How to Style Structured Tote Bags');
    }
}
