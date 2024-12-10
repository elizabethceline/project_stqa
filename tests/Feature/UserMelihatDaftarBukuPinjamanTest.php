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

        $this->customer = Customer::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $this->post(route('user.login.validate'), [
            'email' => $this->customer->email,
            'password' => 'password',
        ]);
    }

    /** @test */
    public function it_displays_the_customer_reserves_page()
    {
        $book = Book::factory()->create();
        $this->customer->books()->attach($book);

        $response = $this->get(route('user.reserves'));

        $response->assertStatus(200);

        $response->assertViewIs('user.reserves');
        $response->assertSee($this->customer->books[0]->name);
        $response->assertSee($this->customer->books[0]->author);
        $response->assertSee($this->customer->books[0]->description);
    }

    /** @test */
    public function it_displays_no_books_message_when_customer_has_no_books_reserved()
    {
        $this->customer->books()->detach();

        $response = $this->get(route('user.reserves'));

        $response->assertStatus(200);

        $response->assertViewIs('user.reserves');
        $response->assertSee('No reserve found');
    }
}
