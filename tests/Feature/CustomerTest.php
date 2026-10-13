<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\PatcheeDataSeeder;
use App\Models\User;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PatcheeDataSeeder::class);
    }

    public function test_customer_can_login_and_access_account_dashboard(): void
    {
        $response = $this->post('/customer/login', [
            'email' => 'customer@thepatchee.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/customer/account');

        $this->assertAuthenticated();

        $accountResponse = $this->get('/customer/account');
        $accountResponse->assertStatus(200);
        $accountResponse->assertSee('WELCOME BACK');
        $accountResponse->assertSee('MY ORDER HISTORY');
        $accountResponse->assertSee('customer@thepatchee.com');
    }

    public function test_customer_can_register(): void
    {
        $response = $this->post('/customer/register', [
            'name' => 'Sara Farooq',
            'email' => 'sara@example.com',
            'phone' => '01899112233',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/customer/account');
        $this->assertDatabaseHas('users', ['email' => 'sara@example.com']);
    }
}
