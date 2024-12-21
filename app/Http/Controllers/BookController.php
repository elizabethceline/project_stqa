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
        $books = Book::where('name', 'like', '%' . $search . '%')->with('customers')->get();
        return response()->json(['success' => 'Book found'], 200);

        if (session()->has('admin')) {
            return view('admin.books', [
                'search' => $search,
                'books' => $books
            ]);
        } else if (session()->has('customer')) {
            return view('user.books', [
                'search' => $search,
                'books' => $books
            ]);
        }
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
            'name' => 'required|string|min:3|max:255',
            'desc' => 'required|string',
            'author' => 'required|string|min:3|max:100',
            'availability' => 'required|numeric|in:0,1',
            'edition' => 'required|string|min:3|max:100',
            'count' => 'required|numeric|min:0',
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'numeric' => ':attribute harus berupa angka',
            'in' => ':attribute harus 0 atau 1',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maksimal :max karakter',
            'count.min' => 'Count minimal :min',
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

        $book = new Book();
        $book->name = $request->name;
        $book->desc = $request->desc;
        $book->author = $request->author;
        $book->availability = $request->availability;
        $book->edition = $request->edition;
        $book->count = $request->count;
        $book->save();

        // return response()->json(['success' => 'Book created successfully'], 200);
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
            'name' => 'required|string|min:3|max:255',
            'desc' => 'required|string',
            'author' => 'required|string|min:3|max:100',
            'availability' => 'required|numeric|in:0,1',
            'edition' => 'required|string|min:3|max:100',
            'count' => 'required|numeric|min:0',
        ];

        $messages = [
            'required' => ':attribute harus diisi',
            'string' => ':attribute harus berupa string',
            'numeric' => ':attribute harus berupa angka',
            'in' => ':attribute harus 0 atau 1',
            'min' => ':attribute minimal :min karakter',
            'max' => ':attribute maksimal :max karakter',
            'count.min' => 'Count minimal :min',
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

    public function delete($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return redirect()->route('admin.books')->with('error', 'Book not found');
        }
        if ($book->customers->count() > 0) {
            return redirect()->route('admin.books')->with('error', 'Book is still reserved by customer');
        }
        $book->delete();
        return redirect()->route('admin.books')->with('success', 'Book deleted successfully');
    }
}
