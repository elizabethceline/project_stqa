<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CustomerReservesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
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
    public function it_displays_no_books_found_message_when_books_is_empty()
    {
        DB::table('books')->delete();

        $response = $this->get(route('user.books'));

        $response->assertStatus(200);
        $response->assertViewIs('user.books');
        $response->assertViewHas('books');
        $response->assertViewHas('search');
        $response->assertSee('Books');
        $response->assertSee('No books found');
    }

    /** @test */
    public function it_searches_for_books()
    {
        $book1 = Book::factory()->create(['name' => 'Book One']);
        $book2 = Book::factory()->create(['name' => 'Book Two']);

        $response = $this->get(route('user.books', ['search_book' => 'Book One']));

        $response->assertStatus(200);
        $response->assertViewIs('user.books');
        $response->assertViewHas('books');
        $response->assertViewHas('search');
        $response->assertSee('Books');
        $response->assertSee($book1->name);
        $response->assertDontSee($book2->name);
    }

    /** @test */
    public function it_displays_no_books_found_message_when_searching_for_non_existent_book()
    {
        $book1 = Book::factory()->create(['name' => 'Book One']);
        $book2 = Book::factory()->create(['name' => 'Book Two']);

        $response = $this->get(route('user.books', ['search_book' => 'Book Three']));

        $response->assertStatus(200);
        $response->assertViewIs('user.books');
        $response->assertViewHas('books');
        $response->assertViewHas('search');
        $response->assertSee('Books');
        $response->assertSee('No books found');
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
