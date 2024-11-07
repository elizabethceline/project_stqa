<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search_book;
        $books = Book::where('name', 'like', '%' . $search . '%')->get();
        if ($books->isEmpty()) {
            return redirect()->route('admin.books')->with('error', 'No book found');
        }
        return view('admin.books', [
            'search' => $search,
            'books' => $books
        ]);
    }

    public function add()
    {
        return view('admin.create_book', [
            'title' => 'Create Book'
        ]);
    }

    public function create(Request $request)
    {
        $validator = [
            'name' => 'required|string',
            'desc' => 'required|string',
            'author' => 'required|string',
            'availability' => 'required|numeric|in:0,1',
            'edition' => 'required|string',
            'count' => 'required|numeric|min:0',
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'numeric' => ':attribute harus berupa angka',
            'in' => ':attribute harus 0 atau 1',
            'min' => ':attribute minimal 0',
        ];

        $attributes = [
            'name' => 'Name',
            'desc' => 'Description',
            'author' => 'Author',
            'availability' => 'Availability',
            'edition' => 'Edition',
            'count' => 'Count',
        ];

        $valid = Validator::make($request->only(['name', 'desc', 'author', 'availability', 'edition', 'count']), $validator, $messages, $attributes);

        if ($valid->fails()) {
            return back()->with('error', $valid->errors()->first());
        }

        Book::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'author' => $request->author,
            'availability' => $request->availability,
            'edition' => $request->edition,
            'count' => $request->count,
        ]);

        return redirect()->route('admin.books')->with('success', 'Book created successfully');
    }

    public function edit($id)
    {
        $book = Book::find($id);
        return view('admin.update_book', [
            'title' => 'Edit Book',
            'book' => $book
        ]);
    }

    public function update(Request $request)
    {
        $validator = [
            'name' => 'required|string',
            'desc' => 'required|string',
            'author' => 'required|string',
            'availability' => 'required|numeric|in:0,1',
            'edition' => 'required|string',
            'count' => 'required|numeric|min:0',
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'numeric' => ':attribute harus berupa angka',
            'in' => ':attribute harus 0 atau 1',
            'min' => ':attribute minimal 0',
        ];

        $attributes = [
            'name' => 'Name',
            'desc' => 'Description',
            'author' => 'Author',
            'availability' => 'Availability',
            'edition' => 'Edition',
            'count' => 'Count',
        ];

        $valid = Validator::make($request->only(['name', 'desc', 'author', 'availability', 'edition', 'count']), $validator, $messages, $attributes);

        if ($valid->fails()) {
            return back()->with('error', $valid->errors()->first());
        }

        $book = Book::find($request->id);
        $book->name = $request->name;
        $book->desc = $request->desc;
        $book->author = $request->author;
        $book->availability = $request->availability;
        $book->edition = $request->edition;
        $book->count = $request->count;
        $book->save();

        return redirect()->route('admin.books')->with('success', 'Book updated successfully');
    }
}
