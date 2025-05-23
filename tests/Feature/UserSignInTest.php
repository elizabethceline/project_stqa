<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSignInTest extends TestCase
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
    public function it_allows_user_to_login_with_correct_credentials(): void
    {
        $response = $this->post(route('user.login.validate'), [
            'email' => 'user@user.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('user.home'));
        $response->assertSessionHas('success', 'Login Berhasil');
        $this->assertTrue(session()->has('customer')); // Check if session is set
    }

    /** @test */
    public function it_prevents_login_with_incorrect_password(): void
    {
        $response = $this->post(route('user.login.validate'), [
            'email' => 'user@user.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Password Salah');
        $this->assertFalse(session()->has('customer')); // Ensure session is not set
    }

    /** @test */
    public function it_requires_email()
    {
        $response = $this->post(route('user.login.validate'), [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus diisi');
        $this->assertFalse(session()->has('customer'));
    }

    /** @test */
    public function it_requires_password()
    {
        $response = $this->post(route('user.login.validate'), [
            'email' => 'user@user.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Password harus diisi');
        $this->assertFalse(session()->has('customer'));
    }

    /** @test */
    public function it_fails_when_email_does_not_exist()
    {
        $response = $this->post(route('user.login.validate'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail tidak ditemukan'); // Check for non-existent email error
    }
}
