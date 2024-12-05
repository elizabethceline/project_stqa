<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminManageCustomersTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function setUp(): void
    {
        parent::setUp();

        //login first
        $admin = Admin::factory()->create();
        $this->post(route('admin.login.validate'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);
    }

    /** @test */
    public function it_displays_a_list_of_customers_with_books()
    {
        $customer = Customer::factory()->create();
        $book = Book::factory()->create();
        $customer->books()->attach($book);

        $response = $this->get(route('admin.users'));

        $response->assertStatus(200);

        $response->assertViewIs('admin.customers');
        $response->assertSee($customer->name);
        $response->assertSee($book->name);
    }

    /** @test */
    public function it_deletes_a_valid_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('admin.users.delete', ['id' => $customer->id]));

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('success', 'User deleted successfully');
    }

    /** @test */
    public function it_fails_to_delete_a_non_existing_customer()
    {
        $response = $this->delete(route('admin.users.delete', ['id' => 999]));

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('error', 'User not found');
    }

    /** @test */
    public function it_fails_to_delete_a_customer_with_books()
    {
        $customer = Customer::factory()->create();
        $book = Book::factory()->create();
        $customer->books()->attach($book);

        $response = $this->delete(route('admin.users.delete', ['id' => $customer->id]));

        $response->assertRedirect(route('admin.users'));
        $response->assertSessionHas('error', 'User cannot be deleted because they still have books');
    }
}
