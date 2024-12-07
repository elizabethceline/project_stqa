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
