@extends('layouts.app')

@section('title', $book->title)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('library.index') }}" class="text-decoration-none">Ryan's Library</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-4">
        @if ($book->cover_image_url)
            <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="img-fluid rounded shadow">
        @else
            <div class="cover-ph rounded d-flex align-items-center justify-content-center"
                style="height: 400px;">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                        class="bi bi-book text-muted" viewBox="0 0 16 16">
                        <path
                            d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                    </svg>
                    <p class="text-muted mt-2">No Cover</p>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="mb-2">
            @if ($book->genre)
                <span class="badge bg-secondary me-1">{{ $book->genre->name }}</span>
            @endif
            <span class="badge bg-info">{{ $book->language }}</span>
        </div>

        <h1 class="fw-bold">{{ $book->title }}</h1>
        <p class="text-muted">{{ $book->author }}</p>

        <ul class="list-unstyled text-muted">
            @if ($book->pages)
                <li>{{ $book->pages }} pages</li>
            @endif
            @if ($book->isbn)
                <li>ISBN: {{ $book->isbn }}</li>
            @endif
        </ul>

        @if ($book->synopsis)
            <h5 class="fw-bold mt-4">Synopsis</h5>
            <p class="text-muted" style="white-space: pre-line;">{{ $book->synopsis }}</p>
        @endif

        <div class="card mt-4">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="text-muted small">Rental per day</div>
                        <div class="fw-bold fs-5 text-primary">{{ formatRupiah($book->daily_price) }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-muted small">Refundable deposit</div>
                        <div class="fw-bold fs-5">{{ formatRupiah($book->deposit_price) }}</div>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted">
                        {{ $book->stock > 0 ? $book->stock . ' copy/copies available' : 'Out of stock' }}
                    </span>
                    @if ($book->stock > 0)
                        @auth
                            <a href="{{ route('library.rent.create', $book) }}" class="btn btn-primary fw-bold">Rent Now</a>
                        @else
                            <div>
                                <a href="{{ route('login') }}" class="btn btn-primary fw-bold">Log in to Rent</a>
                                <div class="text-muted small mt-2">Renting needs an account.
                                    <a href="{{ route('register') }}" class="text-decoration-none">Create one free</a>
                                    — and track the books you own with Book Log.</div>
                            </div>
                        @endauth
                    @else
                        <button class="btn btn-secondary" disabled>Out of stock</button>
                    @endif
                </div>
            </div>
        </div>

        @if ($book->goodreads_url)
            <a href="{{ $book->goodreads_url }}" target="_blank" rel="noopener"
                class="btn btn-outline-secondary mt-3">View on Goodreads</a>
        @endif
    </div>
</div>
@endsection
