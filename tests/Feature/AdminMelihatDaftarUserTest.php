<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMelihatDaftarUserTest extends TestCase
{
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
    public function it_displays_no_users_found_message_when_no_customers_exist()
    {
        DB::table('customers')->delete();

        $response = $this->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers');
        $response->assertSee('No users found');
    }

    /** @test */
    public function it_displays_no_books_message_when_user_has_no_books()
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('admin.users'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers');
        $response->assertSee($customer->name);
        $response->assertSee('No books found');
    }
}
