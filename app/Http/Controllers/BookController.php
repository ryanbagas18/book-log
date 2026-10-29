<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Services\BookApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('author', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('ownership')) {
            $query->where('ownership', $request->ownership);
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'latest' => $query->latest(),
            'oldest' => $query->oldest(),
            'title' => $query->orderBy('title'),
            'price_desc' => $query->orderByDesc('price'),
            'price_asc' => $query->orderBy('price'),
            'rating' => $query->orderByDesc('rating'),
            default => $query->latest(),
        };

        $books = $query->paginate(20)->withQueryString();

        // Stats (only for owned books)
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $spentThisMonth = Book::where('ownership', 'owned')
            ->whereMonth('purchase_date', $currentMonth)
            ->whereYear('purchase_date', $currentYear)
            ->sum('price');

        $totalSpentAllTime = Book::where('ownership', 'owned')->sum('price');

        $finishedThisYear = Book::where('status', 'finished')
            ->whereYear('finished_at', $currentYear)
            ->count();

        return view('books.index', compact(
            'books',
            'spentThisMonth',
            'totalSpentAllTime',
            'finishedThisYear'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Search books via external API.
     */
    public function searchBooks(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'title');

        if (empty($query)) {
            return response()->json(['books' => []]);
        }

        $bookApiService = new BookApiService();
        $books = $bookApiService->search($query, $type);

        return response()->json(['books' => $books]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            // Delete old cover if exists
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
