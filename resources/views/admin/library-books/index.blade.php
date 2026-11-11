@extends('layouts.admin')

@section('title', 'Library Books')

@section('admin')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">Library Books</h1>
        <p class="text-muted mb-0">Rental catalog for Ryan Library.</p>
    </div>
    <a href="{{ route('admin.library-books.create') }}" class="btn btn-primary fw-bold">Add Book</a>
</div>

<div class="alert alert-warning py-2">
    Books with rental history cannot be hard-deleted; deleting only archives them (soft delete).
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.library-books.index') }}" class="d-flex gap-2">
            <input type="text" name="q" class="form-control" placeholder="Search title or author"
                value="{{ request('q') }}">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Daily</th>
                    <th>Deposit</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <td style="width: 48px;">
                            @if ($book->cover_image_url)
                                <img src="{{ $book->cover_image_url }}" alt="" class="rounded"
                                    style="width: 32px; height: 48px; object-fit: cover;">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author ?? '—' }}</td>
                        <td>{{ $book->genre?->name ?? '—' }}</td>
                        <td>{{ formatRupiah($book->daily_price) }}</td>
                        <td>{{ formatRupiah($book->deposit_price) }}</td>
                        <td>{{ $book->stock }}</td>
                        <td>
                            @if ($book->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.library-books.edit', $book) }}"
                                class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.library-books.destroy', $book) }}"
                                class="d-inline" onsubmit="return confirm('Archive this book? It will be hidden from the catalog.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No library books yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $books->links() }}
</div>
@endsection
