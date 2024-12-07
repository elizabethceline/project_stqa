<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserMengembalikanBukuTest extends TestCase
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
    public function it_allows_customer_to_return_a_book()
    {
        $book = Book::factory()->create([
            'availability' => 1,
            'count' => 5,
        ]);

        $this->customer->books()->attach($book);

        $response = $this->delete(route('user.books.return', ['id' => $book->id]));

        $response->assertRedirect(route('user.books'));
        $response->assertSessionHas('success', 'Book returned successfully');
    }
}
