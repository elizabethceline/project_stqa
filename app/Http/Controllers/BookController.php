<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::all();
        return view('admin.books', [
            'books' => $books
        ]);
    }

    public function search(Request $request)
    {
        $search = $request->search_book;
        $books = Book::where('name', 'like', '%' . $search . '%')->get();
        return view('admin.books', [
            'search' => $search,
            'books' => $books
        ]);
    }
}
