<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminMelihatDaftarBukuTest extends TestCase
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
}
