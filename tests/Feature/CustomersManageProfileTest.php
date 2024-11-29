<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomersManageProfileTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    protected $customer;
    public function setUp(): void
    {
        parent::setUp();

        //login first
        $this->customer = Customer::factory()->create([
            'password' => bcrypt('password'), // Pastikan password di-hash
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

    /** @test */
    public function it_succesfully_updates_profile_with_valid_data()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => 'John Doe',
            'email' => 'hai@gmail.com',
            'bio' => 'This is a test bio.',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'email' => 'hai@gmail.com',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully');
    }

    /** @test */
    public function it_fails_to_update_profile_with_missing_field()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => '',
            'email' => '',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name harus diisi');
    }

    /** @test */
    public function it_fails_to_update_profile_with_invalid_email()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus berupa email');
    }

    /** @test */
    public function it_fails_to_update_profile_with_existing_email()
    {
        $existingCustomer = Customer::factory()->create();

        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => 'John Doe',
            'email' => $existingCustomer->email,
            'bio' => 'This is a test bio.',
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail sudah digunakan');
    }
}
