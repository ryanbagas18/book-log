@extends('layouts.admin')

@section('title', 'Genres')

@section('admin')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Genres</h1>
        <p class="text-muted mb-0">Catalog genres for Ryan Library.</p>
    </div>
    <a href="{{ route('admin.genres.create') }}" class="btn btn-primary fw-bold">Add Genre</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Library Books</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($genres as $genre)
                    <tr>
                        <td>{{ $genre->name }}</td>
                        <td class="text-muted">{{ $genre->slug }}</td>
                        <td>{{ $genre->library_books_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.genres.edit', $genre) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.genres.destroy', $genre) }}"
                                class="d-inline" onsubmit="return confirm('Delete this genre?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No genres yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $genres->links() }}
</div>
@endsection
