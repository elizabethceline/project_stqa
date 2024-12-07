<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserMelihatDaftarBukuPinjamanTest extends TestCase
{
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
    public function it_displays_the_customer_reserves_page()
    {
        //attach book to this customer
        $book = Book::factory()->create();
        $this->customer->books()->attach($book);

        $response = $this->get(route('user.reserves'));

        $response->assertStatus(200);

        $response->assertViewIs('user.reserves');
        $response->assertSee($this->customer->books[0]->name);
        $response->assertSee($this->customer->books[0]->author);
        $response->assertSee($this->customer->books[0]->description);
    }
}
