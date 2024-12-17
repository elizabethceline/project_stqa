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
            'name' => 'New Book',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => -5,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Count minimal 0');
        $this->assertDatabaseMissing('books', [
            'name' => 'New Book',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => -5,
        ]);
    }


    /** @test */
    public function it_fails_to_create_book_with_missing_fields()
    {
        $book = [
            'name' => '',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name harus diisi');
        $this->assertDatabaseMissing('books', [
            'name' => '',
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_successfully_creates_book_with_254_characters_name()
    {
        $book = [
            'name' => str_repeat('a', 254),
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
            'name' => str_repeat('a', 254),
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_successfully_creates_book_with_255_characters_name()
    {
        $book = [
            'name' => str_repeat('a', 255),
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
            'name' => str_repeat('a', 255),
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_fails_to_create_book_with_256_characters_name()
    {
        $book = [
            'name' => str_repeat('a', 256),
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Name maksimal 255 karakter');
        $this->assertDatabaseMissing('books', [
            'name' => str_repeat('a', 256),
            'desc' => 'Book description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => 'First',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_successfully_creates_book_with_thai_characters()
    {
        $book = [
            'name' => 'ชื่อหนังสือ',
            'desc' => 'คำอธิบายหนังสือ',
            'author' => 'ชื่อผู้แต่ง',
            'availability' => 1,
            'edition' => 'ฉบับที่ 1',
            'count' => 10,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book created successfully');
        $this->assertDatabaseHas('books', [
            'name' => 'ชื่อหนังสือ',
            'desc' => 'คำอธิบายหนังสือ',
            'author' => 'ชื่อผู้แต่ง',
            'availability' => 1,
            'edition' => 'ฉบับที่ 1',
            'count' => 10,
        ]);
    }

    /** @test */
    public function it_successfully_creates_book_with_chinese_characters()
    {
        $book = [
            'name' => '书名',
            'desc' => '书的描述',
            'author' => '作者',
            'availability' => 1,
            'edition' => '第一版',
            'count' => 10,
        ];

        $response = $this->post(route('admin.books.create'), $book);

        $response->assertRedirect(route('admin.books'));
        $response->assertSessionHas('success', 'Book created successfully');
        $this->assertDatabaseHas('books', [
            'name' => '书名',
            'desc' => '书的描述',
            'author' => '作者',
            'availability' => 1,
            'edition' => '第一版',
            'count' => 10,
        ]);
    }
}
