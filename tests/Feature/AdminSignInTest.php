<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSignInTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test admin user
        Admin::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
    }

    /** @test */
    public function it_allows_admin_to_login_with_correct_credentials()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('success', 'Login Berhasil');
        $this->assertTrue(session()->has('admin')); // Check if session is set
    }

    /** @test */
    public function it_prevents_login_with_incorrect_password()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Password Salah');
        $this->assertFalse(session()->has('admin')); // Ensure session is not set
    }

    /** @test */
    public function it_requires_email()
    {
        $response = $this->post(route('admin.login.validate'), [
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus diisi');
        $this->assertFalse(session()->has('admin'));
    }

    /** @test */
    public function it_requires_password()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'admin@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Password harus diisi');
        $this->assertFalse(session()->has('admin'));
    }

    /** @test */
    public function it_fails_when_email_does_not_exist()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail tidak ditemukan'); // Check for non-existent email error
    }
}
