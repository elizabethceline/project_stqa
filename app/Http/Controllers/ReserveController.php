<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Customer;
use Illuminate\Http\Request;

class ReserveController extends Controller
{
    public function showReserves()
    {
        $books = Customer::where('id', session('customer'))->first()->books;
        return view('user.reserves', compact('books'));
    }

    public function reserve($id)
    {
        $book = Book::find($id);

        if ($book->count == 0 || $book->availability == 0) {
            return redirect()->route('user.books')->with('error', 'Book is not available');
        }

        $book->count -= 1;
        $book->save();

        $book->customers()->attach(session()->get('customer'));

        return redirect()->route('user.reserves')->with('success', 'Book reserved successfully');
    }

    public function return($id)
    {
        $book = Book::find($id);

        $book->count += 1;
        $book->save();

        $book->customers()->detach(session()->get('customer'));

        return redirect()->route('user.books')->with('success', 'Book returned successfully');
    }
}
