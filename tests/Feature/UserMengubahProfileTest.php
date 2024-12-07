<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserMengubahProfileTest extends TestCase
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
    public function it_fails_to_update_profile_with_missing_name()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => '',
            'email' => 'hai@gmail.com',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name harus diisi');
    }

    /** @test */
    public function it_fails_to_update_profile_with_missing_email()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => 'John Doe',
            'email' => '',
            'bio' => 'This is a test bio.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'E-mail harus diisi');
    }

    /** @test */
    public function it_fails_to_update_profile_with_invalid_email()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'bio' => 'This is a test bio.',
        ]);

        $this->assertDatabaseMissing('customers', [
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

    /** @test */
    public function it_successfully_updates_profile_with_99_characters_name()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => str_repeat('a', 99),
            'email' => $this->customer->email
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => str_repeat('a', 99),
            'email' => $this->customer->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully');
    }

    /** @test */
    public function it_successfully_updates_profile_with_100_characters_name()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => str_repeat('a', 100),
            'email' => $this->customer->email
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => str_repeat('a', 100),
            'email' => $this->customer->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully');
    }

    /** @test */
    public function it_fails_to_update_profile_with_101_characters_name()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => str_repeat('a', 101),
            'email' => $this->customer->email
        ]);

        $this->assertDatabaseMissing('customers', [
            'name' => str_repeat('a', 101),
            'email' => $this->customer->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name maksimal 100 karakter');
    }

    /** @test */
    public function it_successfully_updates_profile_with_korean_characters_name()
    {
        $response = $this->put(route('user.profile.update', ['id' => $this->customer->id]), [
            'name' => '홍길동',
            'email' => $this->customer->email
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => '홍길동',
            'email' => $this->customer->email
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profile updated successfully');
    }
}
