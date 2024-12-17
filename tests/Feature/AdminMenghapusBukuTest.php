<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminMenghapusBukuTest extends TestCase
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
}
