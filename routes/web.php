<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
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

    Route::get('/home', [AdminController::class, 'index'])->name('home');
});
