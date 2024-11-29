<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminCRUDBookTest extends TestCase
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
    public function it_displays_list_of_books()
    {
        $book1 = Book::factory()->create(['name' => 'Book One']);
        $book2 = Book::factory()->create(['name' => 'Book Two']);

        $response = $this->get(route('admin.books'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.books');
        $response->assertViewHas('books');
        $response->assertViewHas('search');
        $response->assertSee('Books');
        $response->assertSee($book1->name);
        $response->assertSee($book2->name);
    }

    /** @test */
    public function it_displays_no_books_found_message_when_books_is_empty()
    {
        DB::table('books')->delete();

        $response = $this->get(route('admin.books'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.books');
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

    /** @test */
    public function it_displays_the_create_book_page()
    {
        // Akses halaman add book
        $response = $this->get(route('admin.books.add'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.create_book');
        $response->assertSee('Create Book');
    }

    /** @test */
    public function it_creates_a_new_book()
    {
        $book = [
            'name' => 'New Book',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book created successfully');

        $this->assertDatabaseHas('books', [
            'name' => 'New Book',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_fails_to_create_book_with_invalid_availability()
    {
        $book = [
            'name' => 'New Book',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 2,
            'edition' => 'First',
            'count' => 1,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Availability harus 0 atau 1');
        $this->assertDatabaseMissing('books', [
            'name' => 'New Book',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 2,
            'edition' => 'First',
            'count' => 1,
        ]);
    }

    /** @test */
    public function it_fails_to_create_book_with_negative_count()
    {
        $book = [
            'name' => 'Book with Negative Count',
            'desc' => 'Description for this book',
            'author' => 'Some Author',
            'availability' => 1,
            'edition' => 'Edition 1',
            'count' => -5,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Count minimal 0');
        $this->assertDatabaseMissing('books', [
            'name' => 'Book with Negative Count',
            'desc' => 'Description for this book',
            'author' => 'Some Author',
            'availability' => 1,
            'edition' => 'Edition 1',
            'count' => -5,
        ]);
    }


    /** @test */
    public function it_fails_to_create_book_with_missing_fields()
    {
        $book = [
            'name' => '',
            'desc' => 'Description 1',
            'author' => 'Author 1',
            'availability' => 0,
            'edition' => '1st',
            'count' => 1,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name harus diisi');
        $this->assertDatabaseMissing('books', [
            'name' => '',
            'desc' => 'Description 1',
            'author' => 'Author 1',
            'availability' => 0,
            'edition' => '1st',
            'count' => 1,
        ]);
    }

    /** @test */
    public function it_displays_the_edit_book_page()
    {
        $book = Book::factory()->create();
        $response = $this->get(route('admin.books.edit', $book->id));

        $response->assertStatus(200);
        $response->assertViewIs('admin.update_book');
        $response->assertSee('Edit Book');
    }

    /** @test */
    public function it_updates_an_existing_book()
    {
        $book = Book::factory()->create();

        $updatedData = [
            'name' => 'Updated Book Name',
            'desc' => 'Updated Book Description',
            'author' => 'Updated Author',
            'availability' => 1,
            'edition' => 'Updated Edition',
            'count' => 10,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $updatedData);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book updated successfully');
        $this->assertDatabaseHas('books', [
            'name' => 'Updated Book Name',
            'desc' => 'Updated Book Description',
            'author' => 'Updated Author',
            'availability' => 1,
            'edition' => 'Updated Edition',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_fails_to_update_book_with_invalid_availability()
    {
        $book = Book::factory()->create();

        $data = [
            'id' => $book->id,
            'name' => $book->name,
            'desc' => $book->desc,
            'author' => $book->author,
            'availability' => 2,
            'edition' => $book->edition,
            'count' => $book->count,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $data);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Availability harus 0 atau 1');
        $this->assertDatabaseMissing('books', [
            'availability' => 2,
        ]);
    }

    /** @test */
    public function it_fails_to_update_book_with_negative_count()
    {
        $book = Book::factory()->create();

        $data = [
            'id' => $book->id,
            'name' => $book->name,
            'desc' => $book->desc,
            'author' => $book->author,
            'availability' => $book->availability,
            'edition' => $book->edition,
            'count' => -2,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $data);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Count minimal 0');
        $this->assertDatabaseMissing('books', [
            'count' => -2,
        ]);
    }

    /** @test */
    public function it_deletes_a_book()
    {
        $book = Book::create([
            'name' => 'Book to delete',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);

        $response = $this->delete(route('admin.books.delete', $book->id));

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book deleted successfully');

        $this->assertDatabaseMissing('books', [
            'id' => $book->id,
            'name' => 'Book to delete',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_fails_to_delete_a_non_existing_book()
    {
        $nonExistentBookId = 9999;

        $response = $this->delete(route('admin.books.delete', $nonExistentBookId));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Book not found');
    }
}
