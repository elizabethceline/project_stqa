<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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
}
