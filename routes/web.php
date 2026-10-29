<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/api/books/search', [BookController::class, 'searchBooks'])->name('books.search');
Route::get('/books/export', [BookController::class, 'export'])->name('books.export');

Route::resource('books', BookController::class);
