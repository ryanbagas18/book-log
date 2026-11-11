<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLibraryBookRequest;
use App\Http\Requests\Admin\UpdateLibraryBookRequest;
use App\Models\Genre;
use App\Models\LibraryBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryBookController extends Controller
{
    public function index(Request $request)
    {
        $books = LibraryBook::with('genre')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('author', 'like', '%' . $request->q . '%');
            })
            ->orderBy('title')
            ->paginate(20)
            ->withQueryString();

        return view('admin.library-books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.library-books.create', [
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function store(StoreLibraryBookRequest $request)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('library-covers', 'public');
        }

        LibraryBook::create($data);

        return redirect()->route('admin.library-books.index')->with('success', 'Library book created successfully!');
    }

    public function edit(LibraryBook $libraryBook)
    {
        return view('admin.library-books.edit', [
            'book' => $libraryBook,
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateLibraryBookRequest $request, LibraryBook $libraryBook)
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('cover_image')) {
            if ($libraryBook->cover_image) {
                Storage::disk('public')->delete($libraryBook->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('library-covers', 'public');
        }

        $libraryBook->update($data);

        return redirect()->route('admin.library-books.index')->with('success', 'Library book updated successfully!');
    }

    public function destroy(LibraryBook $libraryBook)
    {
        $libraryBook->delete();

        return redirect()->route('admin.library-books.index')->with('success', 'Library book deleted successfully!');
    }

    private function validatedData($request): array
    {
        $data = $request->validated();
        $data['deposit_price'] = $data['deposit_price'] ?? 0;
        $data['stock'] = $data['stock'] ?? 1;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
