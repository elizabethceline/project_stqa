<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserMeminjamBukuTest extends TestCase
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
    public function it_allows_customer_to_reserve_a_valid_book()
    {
        $book = Book::factory()->create([
            'availability' => 1,
            'count' => 5,
        ]);

        $response = $this->post(route('user.books.reserve', ['id' => $book->id]));

        $response->assertRedirect(route('user.reserves'));
        $response->assertSessionHas('success', 'Book reserved successfully');
    }

    /** @test */
    public function it_fails_to_reserve_an_unavailable_book()
    {
        $book = Book::factory()->create([
            'availability' => 0,
            'count' => 5,
        ]);

        $response = $this->post(route('user.books.reserve', ['id' => $book->id]));

        $response->assertRedirect(route('user.books'));
        $response->assertSessionHas('error', 'Book is not available');
    }

    /** @test */
    public function it_fails_to_reserve_a_book_with_zero_count()
    {
        $book = Book::factory()->create([
            'availability' => 1,
            'count' => 0,
        ]);

        $response = $this->post(route('user.books.reserve', ['id' => $book->id]));

        $response->assertRedirect(route('user.books'));
        $response->assertSessionHas('error', 'Book is not available');
    }

    /** @test */
    public function it_fails_to_reserve_an_already_reserved_book()
    {
        $book = Book::factory()->create([
            'availability' => 1,
            'count' => 5,
        ]);

        $this->customer->books()->attach($book);

        $response = $this->post(route('user.books.reserve', ['id' => $book->id]));

        $response->assertRedirect(route('user.books'));
        $response->assertSessionHas('error', 'Book already reserved');
    }
}
