@extends('layouts.app')

@section('title', "Ryan's Library")

@section('content')
<div class="mb-4">
    <h1 class="fw-bold">Ryan's Library</h1>
    <p class="text-muted mb-0">Browse our book rental catalog.</p>
</div>

<!-- Search -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('library.index') }}" class="d-flex gap-2">
            @if (request('genre'))
                <input type="hidden" name="genre" value="{{ request('genre') }}">
            @endif
            <input type="text" name="q" class="form-control" placeholder="Search title or author"
                value="{{ request('q') }}">
            <button type="submit" class="btn btn-primary">Search</button>
            @if (request()->hasAny(['q', 'genre']))
                <a href="{{ route('library.index') }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Genre pills -->
@if ($genres->isNotEmpty())
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('library.index', request('q') ? ['q' => request('q')] : []) }}"
            class="btn btn-sm {{ ! request('genre') ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
        @foreach ($genres as $genre)
            <a href="{{ route('library.index', array_filter(['q' => request('q'), 'genre' => $genre->slug])) }}"
                class="btn btn-sm {{ request('genre') === $genre->slug ? 'btn-primary' : 'btn-outline-secondary' }}">
                {{ $genre->name }}
            </a>
        @endforeach
    </div>
@endif

<!-- Catalog grid -->
@if ($books->count() > 0)
    <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
        @foreach ($books as $book)
            <div class="col">
                <a href="{{ route('library.show', $book) }}" class="text-decoration-none">
                    <div class="card h-100 book-card shadow-sm" style="transition: all 0.3s ease; border: none;">
                        @if ($book->cover_image_url)
                            <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->title }}"
                                style="height: 280px; object-fit: cover; border-radius: 12px 12px 0 0;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 280px; border-radius: 12px 12px 0 0;">
                                <div class="text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                        fill="currentColor" class="bi bi-book text-muted" viewBox="0 0 16 16">
                                        <path
                                            d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                                    </svg>
                                    <p class="text-muted small mt-2">No Cover</p>
                                </div>
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                @if ($book->genre)
                                    <span class="badge bg-secondary me-1">{{ $book->genre->name }}</span>
                                @endif
                                <span class="badge bg-info">{{ $book->language }}</span>
                            </div>
                            <h5 class="card-title text-truncate fw-bold text-body" title="{{ $book->title }}"
                                style="font-size: 1rem;">{{ $book->title }}</h5>
                            <p class="card-text text-muted small mb-1">{{ $book->author }}</p>
                            <p class="card-text fw-bold mb-1 text-primary">{{ formatRupiah($book->daily_price) }}
                                <span class="text-muted fw-normal small">/day</span></p>
                            <p class="card-text text-muted small mb-0">
                                {{ $book->stock > 0 ? $book->stock . ' available' : 'Out of stock' }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body text-center text-muted py-5">
            No books found. Try a different search or genre.
        </div>
    </div>
@endif
@endsection
