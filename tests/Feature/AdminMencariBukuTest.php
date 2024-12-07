<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMencariBukuTest extends TestCase
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
    public function it_searches_for_books()
    {
        $book1 = Book::factory()->create(['name' => 'Book One']);
        $book2 = Book::factory()->create(['name' => 'Book Two']);

        $response = $this->get(route('admin.books', ['search_book' => 'Book One']));

        $response->assertStatus(200);
        $response->assertViewIs('admin.books');
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

        $response = $this->get(route('admin.books', ['search_book' => 'Book Three']));

        $response->assertStatus(200);
        $response->assertViewIs('admin.books');
        $response->assertViewHas('books');
        $response->assertViewHas('search');
        $response->assertSee('Books');
        $response->assertSee('No books found');
    }
}
