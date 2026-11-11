<?php

use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\LibraryBookController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('library.index'));

Route::get('/library', [CatalogController::class, 'index'])->name('library.index');
Route::get('/library/{libraryBook}', [CatalogController::class, 'show'])->name('library.show');
Route::get('/terms', [CatalogController::class, 'terms'])->name('terms');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store')->middleware('throttle:6,1');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store')->middleware('throttle:6,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Books are personal: resolve {book} only within the current user's collection.
Route::bind('book', function (string $value) {
    $user = auth()->user();
    abort_if(! $user, 404);

    return $user->books()->findOrFail($value);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    Route::get('/rentals/{rental}/proof', [RentalController::class, 'proof'])->name('rentals.proof');

    Route::middleware('profile.complete')->group(function () {
        Route::get('/library/{libraryBook}/rent', [RentalController::class, 'create'])
            ->name('library.rent.create');
        Route::post('/library/{libraryBook}/rent', [RentalController::class, 'store'])
            ->name('library.rent.store');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/api/books/search', [BookController::class, 'searchBooks'])->name('books.search');
    Route::get('/books/export', [BookController::class, 'export'])->name('books.export');

    Route::resource('books', BookController::class);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('genres', GenreController::class)->except('show');
    Route::resource('library-books', LibraryBookController::class)->except('show');
});
