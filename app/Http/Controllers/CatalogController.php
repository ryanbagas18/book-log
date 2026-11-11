<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\LibraryBook;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $books = LibraryBook::active()
            ->with('genre')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->q . '%')
                        ->orWhere('author', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->filled('genre'), function ($query) use ($request) {
                $query->whereHas('genre', fn ($q) => $q->where('slug', $request->genre));
            })
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $genres = Genre::whereHas('libraryBooks', fn ($q) => $q->active())
            ->orderBy('name')
            ->get();

        return view('library.index', compact('books', 'genres'));
    }

    public function show(LibraryBook $libraryBook)
    {
        abort_unless($libraryBook->is_active, 404);

        $libraryBook->load('genre');

        return view('library.show', ['book' => $libraryBook]);
    }

    public function terms()
    {
        return view('library.terms');
    }
}
