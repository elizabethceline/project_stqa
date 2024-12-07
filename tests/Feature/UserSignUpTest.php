<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomersSignUpTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    /** @test */
    public function it_allows_customers_to_sign_up_with_valid_data()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'bio' => 'This is a test bio.',
        ];

        $response = $this->post(route('user.signup.validate'), $data);

        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $this->assertTrue(Hash::check('password123', Customer::where('email', 'john@example.com')->first()->password));
        $response->assertRedirect(route('user.login'));
        $response->assertSessionHas('success', 'Account created successfully');
    }

    /** @test */
    public function it_fails_to_sign_up_with_missing_name()
    {
        $response = $this->post(route('user.signup.validate'), [
            'name' => '',
            'email' => 'josh@example.com',
            'password' => 'password123',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name harus diisi');
    }

    /** @test */
    public function it_fails_to_sign_up_with_missing_email()
    {
        $response = $this->post(route('user.signup.validate'), [
            'name' => 'Josh Doe',
            'email' => '',
            'password' => 'password123',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus diisi');
    }

    /** @test */
    public function it_fails_to_sign_up_with_missing_password()
    {
        $response = $this->post(route('user.signup.validate'), [
            'name' => 'Josh Doe',
            'email' => 'josh@example.com',
            'password' => '',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Password harus diisi');
    }

    /** @test */
    public function it_fails_to_sign_up_with_invalid_email()
    {
        $response = $this->post(route('user.signup.validate'), [
            'name' => 'Jane Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus berupa email');
    }

    /** @test */
    public function it_fails_to_sign_up_with_existing_email()
    {
        DB::table('customers')->delete();

        $existingCustomer = Customer::create([
            'name' => 'John Smith',
            'email' => 'duplicate@example.com',
            'password' => bcrypt('password123'),
            'bio' => 'This is an existing user.',
        ]);

        $response = $this->post(route('user.signup.validate'), [
            'name' => 'Another User',
            'email' => 'duplicate@example.com',
            'password' => 'password123',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail sudah terdaftar');
    }
}
