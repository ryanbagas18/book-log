@extends('layouts.app')

@section('title', 'My Books')

@section('content')
    <!-- Header Section -->
    <div class="mb-5">
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('books.export') }}" class="btn btn-success d-inline-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-file-earmark-arrow-down me-2" viewBox="0 0 16 16">
                    <path
                        d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
                    <path
                        d="M8 5a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 9.293V5.5A.5.5 0 0 1 8 5z" />
                </svg>
                Export CSV
            </a>
            <a href="{{ route('books.create') }}" class="btn btn-primary shadow d-inline-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-plus-lg me-2" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                </svg>
                Add Book
            </a>
        </div>
    </div>


    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 shadow" style="background-color: #0d6efd;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                    class="bi bi-cash-coin" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M5.5 9.5A.5.5 0 0 1 6 9h4a.5.5 0 0 1 0 1H6a.5.5 0 0 1-.5-.5z" />
                                    <path
                                        d="M5.5 8a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm4.5.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0z" />
                                    <path
                                        d="M2 4.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1 opacity-75">Spent This Month</h6>
                            <h2 class="card-text mb-0 fw-bold">{{ formatRupiah($spentThisMonth) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white h-100 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                    class="bi bi-wallet2" viewBox="0 0 16 16">
                                    <path
                                        d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9A1.5 1.5 0 0 1 1.5 3H2V1.78c0-.398.158-.78.437-1.061L12.136.326zM2.5 4a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm4.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1 opacity-75">Total Collection Value</h6>
                            <h2 class="card-text mb-0 fw-bold">{{ formatRupiah($totalSpentAllTime) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white h-100 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                    class="bi bi-trophy-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M2.5.5A.5.5 0 0 1 3 0h10a.5.5 0 0 1 .5.5c0 .538-.012 1.05-.034 1.536a3 3 0 1 1-1.133 5.89c-.79 1.865-1.878 2.777-2.833 3.011v2.173l1.425.356c.194.048.377.135.537.255L13.3 15.1a.5.5 0 0 1-.3.9H3a.5.5 0 0 1-.3-.9l1.838-1.379c.16-.12.343-.207.537-.255L6.5 13.11v-2.173c-.955-.234-2.043-1.146-2.833-3.012a3 3 0 1 1-1.132-5.89A33.076 33.076 0 0 1 2.5.5zm.099 2.54a2 2 0 0 0 .72 3.935c-.333-1.05-.588-2.346-.72-3.935zm10.083 3.935a2 2 0 0 0 .72-3.935c-.133 1.59-.388 2.885-.72 3.935z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1 opacity-75">Books Finished This Year</h6>
                            <h2 class="card-text mb-0 fw-bold">{{ $finishedThisYear }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ route('books.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 16 16">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </span>
                        <input type="text" class="form-control" name="q" placeholder="Search title or author..."
                            value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="status">
                        <option value="">All Statuses</option>
                        <option value="to_read" {{ request('status') == 'to_read' ? 'selected' : '' }}>To Read</option>
                        <option value="reading" {{ request('status') == 'reading' ? 'selected' : '' }}>Reading</option>
                        <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                        <option value="dropped" {{ request('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="ownership">
                        <option value="">All Ownership</option>
                        <option value="owned" {{ request('ownership') == 'owned' ? 'selected' : '' }}>Owned</option>
                        <option value="skimmed" {{ request('ownership') == 'skimmed' ? 'selected' : '' }}>Skimmed</option>
                        <option value="borrowed" {{ request('ownership') == 'borrowed' ? 'selected' : '' }}>Borrowed
                        </option>
                        <option value="wishlist" {{ request('ownership') == 'wishlist' ? 'selected' : '' }}>Wishlist
                        </option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="language">
                        <option value="">All Languages</option>
                        <option value="Indonesian" {{ request('language') == 'Indonesian' ? 'selected' : '' }}>Indonesian
                        </option>
                        <option value="English" {{ request('language') == 'English' ? 'selected' : '' }}>English</option>
                        <option value="Other" {{ request('language') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="sort">
                        <option value="latest" {{ request('sort', 'latest') == 'latest' ? 'selected' : '' }}> Latest
                        </option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}> Oldest</option>
                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}> Title (A-Z)</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}> Price
                            (High-Low)</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}> Price (Low-High)
                        </option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}> Rating (High-Low)
                        </option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-funnel-fill" viewBox="0 0 16 16">
                            <path
                                d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z" />
                        </svg>
                    </button>
                </div>
                @if (request()->hasAny(['q', 'status', 'ownership', 'language', 'sort']))
                    <div class="col-md-1">
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-x-lg" viewBox="0 0 16 16">
                                <path
                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
                            </svg>
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Books Grid -->
    @if ($books->count() > 0)
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
            @foreach ($books as $book)
                <div class="col">
                    <div class="card h-100 book-card shadow-sm" style="transition: all 0.3s ease; border: none;">
                        @if ($book->cover_image_url)
                            <div class="position-relative">
                                <img src="{{ $book->cover_image_url }}" class="card-img-top" alt="{{ $book->title }}"
                                    style="height: 280px; object-fit: cover; border-radius: 12px 12px 0 0;">
                                @if ($book->status == 'finished')
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success rounded-pill shadow"></span>
                                    </div>
                                @endif
                            </div>
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
                                <span class="badge bg-secondary me-1">{{ $book->language }}</span>
                                @if ($book->condition)
                                    <span
                                        class="badge bg-info me-1">{{ $book->condition == 'new' ? 'New' : 'Preloved' }}</span>
                                @endif
                                @if ($book->ownership != 'owned')
                                    <span class="badge bg-warning text-dark">{{ $book->ownership_label }}</span>
                                @endif
                            </div>
                            <h5 class="card-title text-truncate fw-bold" title="{{ $book->title }}"
                                style="font-size: 1rem;">{{ $book->title }}</h5>
                            <p class="card-text text-muted small mb-1">{{ $book->author }}</p>
                            @if ($book->purchase_place)
                                <p class="card-text text-muted small mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                                        <path
                                            d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94zM8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10z" />
                                        <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                    {{ $book->purchase_place }}
                                </p>
                            @endif
                            <p class="card-text fw-bold mb-1 text-primary">{{ $book->formatted_price }}</p>
                            @if ($book->rating)
                                <div class="d-flex gap-1 mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $book->rating)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-star-fill text-warning"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-star" style="color: #444;"
                                                viewBox="0 0 16 16">
                                                <path
                                                    d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957-2.913-2.835 4.022-.58 1.795-3.647 1.795 3.647 4.022.58-2.913 2.835.694 3.957-3.686-1.894z" />
                                            </svg>
                                        @endif
                                    @endfor
                                </div>
                            @endif
                            <div class="mt-auto">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('books.show', $book) }}"
                                        class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('books.edit', $book) }}"
                                        class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $book->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                            <path fill-rule="evenodd"
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div class="modal fade" id="deleteModal{{ $book->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-exclamation-triangle-fill text-danger me-2"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </svg>
                                    Delete Book
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong>"{{ $book->title }}"</strong>?</p>
                                <p class="text-muted small">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('books.destroy', $book) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash me-1" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                            <path fill-rule="evenodd"
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor"
                    class="bi bi-book text-muted" viewBox="0 0 16 16">
                    <path
                        d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                </svg>
            </div>
            <h4 class="fw-bold mb-3">No books found</h4>
            <p class="text-muted mb-4">Try adjusting your filters or add a new book to your collection.</p>
            <a href="{{ route('books.create') }}" class="btn btn-primary btn-lg shadow">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-plus-lg me-2" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                </svg>
                Add Your First Book
            </a>
        </div>
    @endif
@endsection

@push('scripts')
    <style>
        .book-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
        }

        .card-img-top {
            transition: transform 0.3s ease;
        }

        .book-card:hover .card-img-top {
            transform: scale(1.05);
        }
    </style>
    <script>
        document.querySelectorAll('.book-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.cursor = 'pointer';
            });
        });
    </script>
@endpush
