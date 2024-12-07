<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTambahBukuBaruTest extends TestCase
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
}
