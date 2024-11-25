<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layout');
});

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('login', [AuthController::class, 'indexAdmin'])->name('login')->withoutMiddleware('admin');
    Route::post('login', [AuthController::class, 'loginAdmin'])->name('login.validate')->withoutMiddleware('admin');
    Route::get('logout', [AuthController::class, 'logoutAdmin'])->name('logout');

    Route::get('/home', [AdminController::class, 'index'])->name('home');
    Route::post('/home', [BookController::class, 'index'])->name('books.search');
    Route::get('/books', [BookController::class, 'index'])->name('books');
    Route::post('/books', [BookController::class, 'index'])->name('books.search');

    Route::get('/books/add', [BookController::class, 'add'])->name('books.add');
    Route::post('/books/add', [BookController::class, 'create'])->name('books.create');

    Route::get('/books/edit/{id}', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/edit/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/delete/{id}', [BookController::class, 'delete'])->name('books.delete');

    Route::get('/users', [CustomerController::class, 'showCustomers'])->name('users');
    Route::delete('/users/delete/{id}', [CustomerController::class, 'delete'])->name('users.delete');
});

Route::prefix('user')->name('user.')->middleware('user')->group(function () {
    Route::get('login', [AuthController::class, 'indexUser'])->name('login')->withoutMiddleware('user');
    Route::post('login', [AuthController::class, 'loginUser'])->name('login.validate')->withoutMiddleware('user');
    Route::get('logout', [AuthController::class, 'logoutUser'])->name('logout');

    Route::get('/home', [CustomerController::class, 'index'])->name('home');
    Route::get('/books', [BookController::class, 'index'])->name('books');
    Route::post('/books', [BookController::class, 'index'])->name('books.search');
    Route::post('/books/reserve/{id}', [BookController::class, 'reserve'])->name('books.reserve');

    Route::get('profile', [CustomerController::class, 'showProfile'])->name('profile');
    Route::put('profile', [CustomerController::class, 'update'])->name('profile.update');
});
