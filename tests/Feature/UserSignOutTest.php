<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSignOutTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create a test user
        if (!Customer::where('email', 'user@user.com')->exists()) {
            Customer::create([
                'email' => 'user@user.com',
                'password' => Hash::make('password'),
                'name' => 'User',
            ]);
        }
    }

    /** @test */
    public function it_logs_out_the_user_and_redirects_to_login_page()
    {
        session(['customer' => 1]);
        $this->assertTrue(session()->has('customer'));
        $response = $this->get(route('user.logout'));
        session()->flush();
        $this->assertFalse(session()->has('customer'));
        $this->assertNull(session('_token'));
        $response->assertRedirect(route('user.login'));
    }
}
