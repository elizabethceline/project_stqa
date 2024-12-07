<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserMelihatProfileTest extends TestCase
{
    use RefreshDatabase;
    protected $customer;
    public function setUp(): void
    {
        parent::setUp();

        //login first
        $this->customer = Customer::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Login pengguna
        $this->post(route('user.login.validate'), [
            'email' => $this->customer->email,
            'password' => 'password',
        ]);
    }

    /** @test */
    public function it_displays_the_customer_profile_page()
    {
        $response = $this->get(route('user.profile'));

        $response->assertStatus(200);

        $response->assertViewIs('user.profile');
        $response->assertSee($this->customer->name);
        $response->assertSee($this->customer->email);
        $response->assertSee($this->customer->bio);
    }
}
