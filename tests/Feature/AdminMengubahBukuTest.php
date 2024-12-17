<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMengubahBukuTest extends TestCase
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
    public function it_fails_to_update_book_name_with_less_than_3_characters()
    {
        $book = Book::factory()->create();

        $data = [
            'id' => $book->id,
            'name' => 'Aa',
            'desc' => $book->desc,
            'author' => $book->author,
            'availability' => $book->availability,
            'edition' => $book->edition,
            'count' => $book->count,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $data);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name minimal 3 karakter');
        $this->assertDatabaseMissing('books', [
            'name' => 'Aa',
        ]);
    }

    /** @test */
    public function it_successfully_updates_book_name_to_3_characters()
    {
        $book = Book::factory()->create();

        $data = [
            'id' => $book->id,
            'name' => 'Aaa',
            'desc' => $book->desc,
            'author' => $book->author,
            'availability' => $book->availability,
            'edition' => $book->edition,
            'count' => $book->count,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $data);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book updated successfully');
        $this->assertDatabaseHas('books', [
            'name' => 'Aaa',
        ]);
    }

    /** @test */
    public function it_successfully_updates_book_name_to_4_characters()
    {
        $book = Book::factory()->create();

        $data = [
            'id' => $book->id,
            'name' => 'Aaaa',
            'desc' => $book->desc,
            'author' => $book->author,
            'availability' => $book->availability,
            'edition' => $book->edition,
            'count' => $book->count,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $data);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book updated successfully');
        $this->assertDatabaseHas('books', [
            'name' => 'Aaaa',
        ]);
    }

    /** @test */
    public function it_successfully_updates_book_name_to_chinese_characters()
    {
        $book = Book::factory()->create();

        $data = [
            'id' => $book->id,
            'name' => '这是一本新书',
            'desc' => $book->desc,
            'author' => $book->author,
            'availability' => $book->availability,
            'edition' => $book->edition,
            'count' => $book->count,
        ];

        $response = $this->put(route('admin.books.update', ['id' => $book->id]), $data);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book updated successfully');
        $this->assertDatabaseHas('books', [
            'name' => '这是一本新书',
        ]);
    }
}
