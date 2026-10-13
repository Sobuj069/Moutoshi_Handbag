<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Services\PathaoService;
use Database\Seeders\PatcheeDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderAndPathaoCourierTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PatcheeDataSeeder::class);
    }

    public function test_customer_can_add_to_cart_and_place_order_with_mh_prefix(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        // Add to cart
        $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
            'variant' => 'Classic Black',
        ])->assertJson(['success' => true]);

        // Access checkout
        $this->get('/checkout')->assertStatus(200)->assertSee('CHECKOUT');

        // Submit checkout
        $response = $this->post('/checkout/order', [
            'customer_name' => 'Fatima Begum',
            'customer_phone' => '01799887766',
            'delivery_address' => 'House 12, Road 5, Dhanmondi, Dhaka',
            'city_zone' => 'inside_dhaka',
            'customer_email' => 'fatima@example.com',
            'payment_method' => 'cash_on_delivery',
            'notes' => 'Call before delivery',
        ]);

        $order = Order::where('customer_phone', '01799887766')->latest('id')->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('MH-', $order->order_number);
        $this->assertEquals('Fatima Begum', $order->customer_name);
        $this->assertEquals('inside_dhaka', $order->city_zone);
        $this->assertCount(1, $order->items);

        $response->assertRedirect(route('order.success', $order->order_number));

        // Order confirmation page
        $this->get(route('order.success', $order->order_number))
            ->assertStatus(200)
            ->assertSee($order->order_number)
            ->assertSee('Fatima Begum');

        // Order tracking page
        $this->get('/pages/track-my-order?order_number=' . $order->order_number)
            ->assertStatus(200)
            ->assertSee($order->order_number);
    }

    public function test_quick_order_checkout_creates_order_with_mh_prefix(): void
    {
        $product = Product::first();

        $response = $this->post('/quick-checkout', [
            'product_id' => $product->id,
            'quantity' => 2,
            'variant' => 'Emerald Green',
            'customer_name' => 'Nusrat Jahan',
            'customer_phone' => '01812345678',
            'delivery_address' => 'GEC Circle, Chittagong',
            'city_zone' => 'outside_dhaka',
            'notes' => 'Urgent parcel',
        ]);

        $order = Order::where('customer_phone', '01812345678')->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('MH-', $order->order_number);
        $this->assertEquals(120, (int) $order->delivery_charge);

        $response->assertRedirect(route('order.success', $order->order_number));
    }

    public function test_admin_can_view_web_orders_list(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();
        $this->assertNotNull($admin);

        // Create an order
        Order::create([
            'order_number' => 'MH-TEST01',
            'customer_name' => 'Test Customer',
            'customer_phone' => '01700000000',
            'delivery_address' => 'Gulshan 2, Dhaka',
            'city_zone' => 'inside_dhaka',
            'delivery_charge' => 60,
            'subtotal' => 1250,
            'total' => 1310,
            'payment_method' => 'cash_on_delivery',
            'order_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('MH-TEST01');
        $response->assertSee('Test Customer');
        $response->assertSee('Web Orders');
    }

    public function test_admin_can_save_pathao_credentials_in_settings(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();

        $response = $this->actingAs($admin)->post('/admin/settings', [
            'pathao_environment' => 'sandbox',
            'pathao_client_id' => 'test_client_id_123',
            'pathao_client_secret' => 'test_secret_456',
            'pathao_client_email' => 'merchant@moutoshi.com',
            'pathao_client_password' => 'secret_pass_789',
            'pathao_store_id' => 9988,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('sandbox', Setting::get('pathao_environment'));
        $this->assertEquals('test_client_id_123', Setting::get('pathao_client_id'));
        $this->assertEquals(9988, (int) Setting::get('pathao_store_id'));
    }

    public function test_admin_can_dispatch_order_to_pathao_courier(): void
    {
        $admin = User::where('email', 'admin@thepatchee.com')->first();

        Setting::set('pathao_environment', 'sandbox');
        Setting::set('pathao_client_id', 'mock_id');
        Setting::set('pathao_client_secret', 'mock_secret');
        Setting::set('pathao_client_email', 'mock_email');
        Setting::set('pathao_client_password', 'mock_pass');
        Setting::set('pathao_store_id', 101);

        $order = Order::create([
            'order_number' => 'MH-DSP01',
            'customer_name' => 'Sadia Islam',
            'customer_phone' => '01912345678',
            'delivery_address' => 'Uttara Sector 7, Dhaka',
            'city_zone' => 'inside_dhaka',
            'delivery_charge' => 60,
            'subtotal' => 1500,
            'total' => 1560,
            'payment_method' => 'cash_on_delivery',
            'order_status' => 'pending',
        ]);

        // Mock Pathao API responses
        Http::fake([
            '*/aladdin/api/v1/issue-token' => Http::response([
                'access_token' => 'mock_access_token_12345',
                'token_type' => 'Bearer',
                'expires_in' => 3600,
            ], 200),
            '*/aladdin/api/v1/orders' => Http::response([
                'type' => 'success',
                'message' => 'Order created successfully',
                'data' => [
                    'consignment_id' => 'PT-CNG-887766',
                    'merchant_order_id' => 'MH-DSP01',
                    'order_status' => 'Pending',
                    'delivery_fee' => 60,
                ],
            ], 200),
        ]);

        $response = $this->actingAs($admin)->post("/admin/orders/{$order->id}/send-pathao", [
            'store_id' => 101,
            'recipient_city' => 1,
            'recipient_zone' => 1,
            'item_weight' => 0.5,
            'amount_to_collect' => 1560,
        ]);

        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('Pathao', $order->courier_name);
        $this->assertEquals('PT-CNG-887766', $order->consignment_id);
        $this->assertEquals('Pending', $order->courier_status);
        $this->assertNotNull($order->shipped_at);

        // Customer tracking page now displays Pathao consignment info
        $this->get('/pages/track-my-order?order_number=' . $order->order_number)
            ->assertStatus(200)
            ->assertSee('PT-CNG-887766')
            ->assertSee('Pathao Courier');
    }
}
