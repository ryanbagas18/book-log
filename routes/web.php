<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

Route::get('/api/books/search', [BookController::class, 'searchBooks'])->name('books.search');

Route::resource('books', BookController::class);
