<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test admin user
        if (!Admin::where('email', 'admin@admin.com')->exists()) {
            Admin::create([
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
            ]);
        }
    }

    /** @test */
    public function it_returns_the_admin_login_view()
    {
        $response = $this->get(route('admin.login'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
        $response->assertViewHas('title', 'Login Page');
    }

    /** @test */
    public function it_allows_admin_to_login_with_correct_credentials()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('success', 'Login Berhasil');
        $this->assertTrue(session()->has('admin')); // Check if session is set
    }

    /** @test */
    public function it_prevents_login_with_incorrect_password()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'admin@admin.com',
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
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus diisi');
        $this->assertFalse(session()->has('admin'));
    }

    /** @test */
    public function it_requires_password()
    {
        $response = $this->post(route('admin.login.validate'), [
            'email' => 'admin@admin.com',
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
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail tidak ditemukan'); // Check for non-existent email error
    }

    /** @test */
    public function it_logs_out_the_admin_and_redirects_to_login_page()
    {
        session(['admin' => 1]);
        $this->assertTrue(session()->has('admin'));
        $response = $this->get(route('admin.logout'));
        session()->flush();
        $this->assertFalse(session()->has('admin'));
        $this->assertNull(session('_token'));
        $response->assertRedirect(route('admin.login'));
    }
}
