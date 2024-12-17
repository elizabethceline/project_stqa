<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UserMencariBukuTest extends TestCase
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
    public function it_successfully_searches_for_books_with_japanese_name()
    {
        $book1 = Book::factory()->create(['name' => '本の一']);
        $book2 = Book::factory()->create(['name' => '本の二']);

        $response = $this->get(route('user.books', ['search_book' => '本の一']));

        $response->assertStatus(200);
        $response->assertViewIs('user.books');
        $response->assertViewHas('books');
        $response->assertViewHas('search');
        $response->assertSee('Books');
        $response->assertSee($book1->name);
        $response->assertDontSee($book2->name);
    }
}
