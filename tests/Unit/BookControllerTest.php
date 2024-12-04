<?php

namespace Tests\Unit;

use App\Http\Controllers\BookController;
use App\Models\Book;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_searches_books_by_name()
    {
        $request = Request::create('/books', 'GET', [
            'search_book' => 'Laravel',
        ]);

        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('where')
            ->once()
            ->with('name', 'like', '%Laravel%')
            ->andReturnSelf();
        $bookMock->shouldReceive('with')
            ->once()
            ->with('customers')
            ->andReturnSelf();
        $bookMock->shouldReceive('get')
            ->once()
            ->andReturn(collect([
                (object) ['id' => 1, 'name' => 'Laravel for Beginners'],
            ]));

        session()->put('admin', true);

        $controller = new BookController();
        $response = $controller->index($request);

        $this->assertEquals('admin.books', $response->name());
        $this->assertArrayHasKey('search', $response->getData());
        $this->assertArrayHasKey('books', $response->getData());
        $this->assertEquals('Laravel', $response->getData()['search']);
        $this->assertCount(1, $response->getData()['books']);
    }

    /** @test */
    public function it_creates_a_book_successfully()
    {
        $request = Request::create('/books', 'POST', [
            'name' => 'New Book',
            'desc' => 'Book Description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => '1st Edition',
            'count' => 10,
        ]);

        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('create')
            ->once()
            ->with($request->all())
            ->andReturnTrue();

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with($request->only(['name', 'desc', 'author', 'availability', 'edition', 'count']), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(false);

        $controller = new BookController();
        $response = $controller->create($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Book created successfully', session('success'));
    }

    /** @test */
    public function it_returns_validation_error_when_creating_book_with_missing_fields()
    {
        $request = Request::create('/books', 'POST', [
            'name' => '', // Invalid data
            'desc' => 'Book Description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => '1st Edition',
            'count' => 10,
        ]);

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with($request->only(['name', 'desc', 'author', 'availability', 'edition', 'count']), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(true);
        $validatorMock->shouldReceive('errors->first')->once()->andReturn('Name is required.');

        $controller = new BookController();
        $response = $controller->create($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Name is required.', session('error'));
    }

    /** @test */
    public function it_returns_validation_error_when_creating_book_with_incorrect_data_type()
    {
        $request = Request::create('/books', 'POST', [
            'name' => 'Joni',
            'desc' => 'Book Description',
            'author' => 'Author Name',
            'availability' => 1,
            'edition' => '1st Edition',
            'count' => 'invalid', // Invalid data type
        ]);

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with($request->only(['name', 'desc', 'author', 'availability', 'edition', 'count']), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(true);
        $validatorMock->shouldReceive('errors->first')->once()->andReturn('Count must be a number.');

        $controller = new BookController();
        $response = $controller->create($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Count must be a number.', session('error'));
    }

    /** @test */
    public function it_updates_a_book_successfully()
    {
        $request = Request::create('/books/update', 'POST', [
            'id' => 1,
            'name' => 'Updated Book',
            'desc' => 'Updated Description',
            'author' => 'Updated Author',
            'availability' => 0,
            'edition' => '2nd Edition',
            'count' => 15,
        ]);

        // Mock Book model
        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturnSelf();
        $bookMock->shouldReceive('save')
            ->once()
            ->andReturnTrue();
        $bookMock->name = 'Updated Book';
        $bookMock->desc = 'Updated Description';
        $bookMock->author = 'Updated Author';
        $bookMock->availability = 0;
        $bookMock->edition = '2nd Edition';
        $bookMock->count = 15;

        $validatorMock = Mockery::mock('alias:Illuminate\Support\Facades\Validator');
        $validatorMock->shouldReceive('make')
            ->once()
            ->with($request->only(['name', 'desc', 'author', 'availability', 'edition', 'count']), Mockery::type('array'), Mockery::type('array'), Mockery::type('array'))
            ->andReturnSelf();
        $validatorMock->shouldReceive('fails')->once()->andReturn(false);

        $controller = new BookController();
        $response = $controller->update($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Book updated successfully', session('success'));
    }

    /** @test */
    public function it_deletes_a_book_successfully()
    {
        $bookMock = Mockery::mock('alias:' . Book::class);
        $bookMock->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturnSelf();
        $bookMock->shouldReceive('delete')
            ->once()
            ->andReturnTrue();
        $bookMock->customers = collect([]); // No customers

        $controller = new BookController();
        $response = $controller->delete(1);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('Book deleted successfully', session('success'));
    }
}
