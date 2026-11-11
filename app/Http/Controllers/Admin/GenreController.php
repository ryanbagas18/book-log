<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGenreRequest;
use App\Http\Requests\Admin\UpdateGenreRequest;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(Request $request)
    {
        $genres = Genre::withCount('libraryBooks')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.genres.index', compact('genres'));
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(StoreGenreRequest $request)
    {
        Genre::create($request->validated());

        return redirect()->route('admin.genres.index')->with('success', 'Genre created successfully!');
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    public function update(UpdateGenreRequest $request, Genre $genre)
    {
        $genre->update($request->validated());

        return redirect()->route('admin.genres.index')->with('success', 'Genre updated successfully!');
    }

    public function destroy(Genre $genre)
    {
        if ($genre->libraryBooks()->exists()) {
            return redirect()->route('admin.genres.index')
                ->with('error', 'Cannot delete a genre that still has library books.');
        }

        $genre->delete();

        return redirect()->route('admin.genres.index')->with('success', 'Genre deleted successfully!');
    }
}
