<div class="row">
    <div class="col-lg-10 mx-auto">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" id="book-form">
            @csrf
            @if (isset($book))
                @method('PUT')
            @endif

            <!-- Search Section -->
            <div class="card mb-4 shadow-sm border-0 bg-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor"
                            class="bi bi-search me-2 text-white" viewBox="0 0 16 16">
                            <path
                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                        <h5 class="mb-0 text-white fw-bold">Search Book from API</h5>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select class="form-select form-select-lg" id="search-type">
                                <option value="title">Title</option>
                                <option value="author">Author</option>
                                <option value="isbn">ISBN</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-lg" id="search-query"
                                placeholder="Type to search books...">
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-light w-100 fw-bold" id="search-btn">
                                <span id="search-spinner" class="spinner-border spinner-border-sm d-none"
                                    role="status"></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search me-1" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                                Search
                            </button>
                        </div>
                    </div>
                    <div id="search-results" class="mt-3"></div>
                </div>
            </div>

            <!-- Auto-fill Indicator -->
            <div id="auto-fill-indicator" class="alert alert-success d-none mb-4 fade-in">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                </svg>
                Book data auto-filled from API! <strong>You can still edit any field.</strong>
                <button type="button" class="btn-close float-end"
                    onclick="this.parentElement.classList.add('d-none')"></button>
            </div>

            <!-- Basic Info Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-book text-white" viewBox="0 0 16 16">
                                <path
                                    d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-bold">Basic Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label fw-bold">Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title"
                                    value="{{ old('title', isset($book) ? $book->title : '') }}" required
                                    placeholder="Enter book title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="author" class="form-label fw-bold">Author</label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror"
                                    id="author" name="author"
                                    value="{{ old('author', isset($book) ? $book->author : '') }}"
                                    placeholder="Enter author name">
                                @error('author')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genre" class="form-label fw-bold">Genre</label>
                                <select class="form-select @error('genre') is-invalid @enderror" id="genre"
                                    name="genre">
                                    <option value="">Select Genre</option>
                                    <option value="Fiction"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Fiction' ? 'selected' : '' }}>
                                        Fiction
                                    </option>
                                    <option value="Non-fiction"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Non-fiction' ? 'selected' : '' }}>
                                        Non-fiction</option>
                                    <option value="Novel"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Novel' ? 'selected' : '' }}>
                                        Novel</option>
                                    <option value="Self-help"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Self-help' ? 'selected' : '' }}>
                                        Self-help
                                    </option>
                                    <option value="Science Fiction"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Science Fiction' ? 'selected' : '' }}>
                                        Science Fiction</option>
                                    <option value="Fantasy"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Fantasy' ? 'selected' : '' }}>
                                        Fantasy
                                    </option>
                                    <option value="Mystery"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Mystery' ? 'selected' : '' }}>
                                        Mystery
                                    </option>
                                    <option value="Thriller"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Thriller' ? 'selected' : '' }}>
                                        Thriller
                                    </option>
                                    <option value="Romance"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Romance' ? 'selected' : '' }}>
                                        Romance
                                    </option>
                                    <option value="Biography"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Biography' ? 'selected' : '' }}>
                                        Biography
                                    </option>
                                    <option value="History"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'History' ? 'selected' : '' }}>
                                        History
                                    </option>
                                    <option value="Business"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Business' ? 'selected' : '' }}>
                                        Business
                                    </option>
                                    <option value="Philosophy"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Philosophy' ? 'selected' : '' }}>
                                        Philosophy</option>
                                    <option value="Psychology"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Psychology' ? 'selected' : '' }}>
                                        Psychology</option>
                                    <option value="Technology"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Technology' ? 'selected' : '' }}>
                                        Technology</option>
                                    <option value="Other"
                                        {{ old('genre', isset($book) ? $book->genre : '') == 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                                @error('genre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="language" class="form-label fw-bold">Language <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('language') is-invalid @enderror" id="language"
                                    name="language" required>
                                    <option value="">Select Language</option>
                                    <option value="Indonesian"
                                        {{ old('language', isset($book) ? $book->language : '') == 'Indonesian' ? 'selected' : '' }}>
                                        Indonesian
                                    </option>
                                    <option value="English"
                                        {{ old('language', isset($book) ? $book->language : '') == 'English' ? 'selected' : '' }}>
                                        English</option>
                                    <option value="Other"
                                        {{ old('language', isset($book) ? $book->language : '') == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="pages" class="form-label fw-bold">Pages</label>
                                <input type="number" class="form-control @error('pages') is-invalid @enderror"
                                    id="pages" name="pages"
                                    value="{{ old('pages', isset($book) ? $book->pages : '') }}" min="1"
                                    max="65535" placeholder="Number of pages">
                                @error('pages')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="isbn" class="form-label fw-bold">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn"
                            name="isbn" value="{{ old('isbn', isset($book) ? $book->isbn : '') }}"
                            placeholder="International Standard Book Number">
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Reading Status Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-bookmark-check text-white" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M10.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                <path
                                    d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z" />
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-bold">Reading Status</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label fw-bold">Reading Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status"
                                    name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="to_read"
                                        {{ old('status', isset($book) ? $book->status : '') == 'to_read' ? 'selected' : '' }}>
                                        To
                                        Read</option>
                                    <option value="reading"
                                        {{ old('status', isset($book) ? $book->status : '') == 'reading' ? 'selected' : '' }}>
                                        Reading</option>
                                    <option value="finished"
                                        {{ old('status', isset($book) ? $book->status : '') == 'finished' ? 'selected' : '' }}>
                                        Finished</option>
                                    <option value="dropped"
                                        {{ old('status', isset($book) ? $book->status : '') == 'dropped' ? 'selected' : '' }}>
                                        Dropped</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="rating" class="form-label fw-bold">Rating</label>
                                <input type="hidden" id="rating" name="rating"
                                    value="{{ old('rating', isset($book) ? $book->rating : '') }}">
                                <div class="star-rating d-flex gap-1" id="star-rating" style="cursor: pointer;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}" style="width: 28px; height: 28px; color: #444;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                        </span>
                                    @endfor
                                </div>
                                <small class="text-muted">Click stars to rate</small>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="started_at" class="form-label fw-bold">Started At</label>
                                <input type="date" class="form-control @error('started_at') is-invalid @enderror"
                                    id="started_at" name="started_at"
                                    value="{{ old('started_at', isset($book) && $book->started_at ? $book->started_at->format('Y-m-d') : '') }}">
                                @error('started_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="finished_at" class="form-label fw-bold">Finished At</label>
                                <input type="date" class="form-control @error('finished_at') is-invalid @enderror"
                                    id="finished_at" name="finished_at"
                                    value="{{ old('finished_at', isset($book) && $book->finished_at ? $book->finished_at->format('Y-m-d') : '') }}">
                                @error('finished_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ownership Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-wallet2 text-white" viewBox="0 0 16 16">
                                <path
                                    d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9A1.5 1.5 0 0 1 1.5 3H2V1.78c0-.398.158-.78.437-1.061L12.136.326zM2.5 4a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm3.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2zm4.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-2z" />
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-bold">Ownership Information</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="ownership" class="form-label fw-bold">Ownership <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('ownership') is-invalid @enderror" id="ownership"
                            name="ownership" required onchange="togglePurchaseFields()">
                            <option value="">Select Ownership</option>
                            <option value="owned"
                                {{ old('ownership', isset($book) ? $book->ownership : '') == 'owned' ? 'selected' : '' }}>
                                Owned
                            </option>
                            <option value="skimmed"
                                {{ old('ownership', isset($book) ? $book->ownership : '') == 'skimmed' ? 'selected' : '' }}>
                                Skimmed (in store)</option>
                            <option value="borrowed"
                                {{ old('ownership', isset($book) ? $book->ownership : '') == 'borrowed' ? 'selected' : '' }}>
                                Borrowed</option>
                            <option value="wishlist"
                                {{ old('ownership', isset($book) ? $book->ownership : '') == 'wishlist' ? 'selected' : '' }}>
                                Wishlist</option>
                        </select>
                        @error('ownership')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="purchase-fields" class="p-3 bg-light rounded">
                        <h6 class="fw-bold mb-3">Purchase Details</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_place" class="form-label">Purchase Place</label>
                                    <input type="text"
                                        class="form-control @error('purchase_place') is-invalid @enderror"
                                        id="purchase_place" name="purchase_place"
                                        value="{{ old('purchase_place', isset($book) ? $book->purchase_place : '') }}"
                                        list="purchase-places" placeholder="Where did you buy it?">
                                    <datalist id="purchase-places">
                                        <option value="Tokopedia">
                                        <option value="Shopee">
                                        <option value="Togamas">
                                        <option value="Gramedia">
                                        <option value="Mochi Pochi">
                                        <option value="Gifted/Free">
                                    </datalist>
                                    @error('purchase_place')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date"
                                        class="form-control @error('purchase_date') is-invalid @enderror"
                                        id="purchase_date" name="purchase_date"
                                        value="{{ old('purchase_date', isset($book) && $book->purchase_date ? $book->purchase_date->format('Y-m-d') : '') }}">
                                    @error('purchase_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Condition</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('condition') is-invalid @enderror"
                                                type="radio" name="condition" id="condition_new" value="new"
                                                {{ old('condition', isset($book) ? $book->condition : '') == 'new' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="condition_new">New</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input @error('condition') is-invalid @enderror"
                                                type="radio" name="condition" id="condition_preloved"
                                                value="preloved"
                                                {{ old('condition', isset($book) ? $book->condition : '') == 'preloved' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="condition_preloved">Preloved</label>
                                        </div>
                                    </div>
                                    @error('condition')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (Rp)</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price"
                                        value="{{ old('price', isset($book) ? $book->price : '') }}" min="0"
                                        placeholder="0">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-image text-white" viewBox="0 0 16 16">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                <path
                                    d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-bold">Cover Image</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="cover_url" class="form-label fw-bold">Cover URL</label>
                        <input type="url" class="form-control @error('cover_url') is-invalid @enderror"
                            id="cover_url" name="cover_url"
                            value="{{ old('cover_url', isset($book) ? $book->getAttributes()['cover_url'] ?? '' : '') }}"
                            placeholder="https://example.com/cover.jpg">
                        @error('cover_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="cover_image" class="form-label fw-bold">Or Upload Cover Image</label>
                        <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                            id="cover_image" name="cover_image" accept="image/jpeg,image/jpg,image/png,image/webp">
                        @if (isset($book) && ($book->cover_image || $book->getAttributes()['cover_url'] ?? null))
                            <div class="mt-3 text-center">
                                <img src="{{ $book->cover_image_url }}" alt="Current cover"
                                    class="img-thumbnail shadow" style="max-height: 250px; max-width: 200px;">
                                <p class="text-muted small mt-2">Current cover</p>
                            </div>
                        @endif
                        @error('cover_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 40px; height: 40px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-pencil-square text-white" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </div>
                        <h5 class="mb-0 fw-bold">Notes</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">Personal Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4"
                            placeholder="Add your thoughts, quotes, or any notes about this book...">{{ old('notes', isset($book) ? $book->notes : '') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 justify-content-center mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-save me-2" viewBox="0 0 16 16">
                        <path
                            d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                    </svg>
                    {{ isset($book) ? 'Update Book' : 'Add Book' }}
                </button>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                        class="bi bi-x-circle me-2" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                    </svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <style>
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .book-result {
            transition: all 0.3s ease;
        }

        .book-result:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
    <script>
        function togglePurchaseFields() {
            const ownership = document.getElementById('ownership').value;
            const purchaseFields = document.getElementById('purchase-fields');

            if (ownership === 'owned') {
                purchaseFields.style.display = 'block';
                document.querySelectorAll('#purchase-fields input, #purchase-fields select').forEach(el => {
                    el.disabled = false;
                });
            } else {
                purchaseFields.style.display = 'none';
                document.querySelectorAll('#purchase-fields input, #purchase-fields select').forEach(el => {
                    el.disabled = true;
                });
            }
        }

        let searchTimeout;

        document.getElementById('search-btn').addEventListener('click', function() {
            const query = document.getElementById('search-query').value.trim();
            const type = document.getElementById('search-type').value;

            if (!query) {
                return;
            }

            const spinner = document.getElementById('search-spinner');
            const resultsDiv = document.getElementById('search-results');

            spinner.classList.remove('d-none');
            resultsDiv.innerHTML =
                '<div class="text-center text-white"><div class="spinner-border" role="status"></div></div>';

            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(function() {
                fetch('{{ route('books.search') }}?q=' + encodeURIComponent(query) + '&type=' + type)
                    .then(response => response.json())
                    .then(data => {
                        spinner.classList.add('d-none');

                        if (data.books && data.books.length > 0) {
                            let html =
                                '<div class="list-group shadow" style="max-height: 450px; overflow-y: auto; border-radius: 15px;">';

                            data.books.forEach(function(book) {
                                const authors = Array.isArray(book.authors) ? book.authors.join(
                                    ', ') : book.authors;
                                const coverUrl = book.cover_url ||
                                    'https://via.placeholder.com/80x120?text=No+Cover';
                                const title = (book.title || 'Unknown Title').replace(/"/g,
                                    '&quot;');
                                const authorStr = (authors || 'Unknown Author').replace(/"/g,
                                    '&quot;');
                                const source = (book.source || '').replace(/"/g, '&quot;');
                                const isbnText = book.isbn ? 'ISBN: ' + book.isbn : '';

                                html +=
                                    '<div class="list-group-item list-group-item-action d-flex gap-3 align-items-center book-result p-3"' +
                                    ' data-title="' + title + '"' +
                                    ' data-author="' + authorStr + '"' +
                                    ' data-language="' + (book.language || '') + '"' +
                                    ' data-pages="' + (book.page_count || '') + '"' +
                                    ' data-isbn="' + (book.isbn || '') + '"' +
                                    ' data-cover-url="' + (book.cover_url || '') + '"' +
                                    ' style="cursor: pointer; border-radius: 10px; margin-bottom: 8px;">' +
                                    '<img src="' + coverUrl +
                                    '" alt="Cover" style="width: 70px; height: 100px; object-fit: cover; border-radius: 8px;" onerror="this.src=\'https://via.placeholder.com/70x100?text=No+Cover\'">' +
                                    '<div class="flex-grow-1">' +
                                    '<h6 class="mb-1 fw-bold" style="color: #333;">' + title +
                                    '</h6>' +
                                    '<p class="mb-1 text-muted small">' + authorStr + '</p>' +
                                    '<small class="text-primary fw-bold">' + source +
                                    '</small>' +
                                    '<br><small class="text-muted">' + isbnText + '</small>' +
                                    '</div>' +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-arrow-right-circle-fill text-primary" viewBox="0 0 16 16">' +
                                    '<path d="M8 0a8 8 0 1 1 0 16A8 8 0 0 1 8 0zM4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H4.5z"/>' +
                                    '</svg>' +
                                    '</div>';
                            });

                            html += '</div>';
                            resultsDiv.innerHTML = html;

                            document.querySelectorAll('.book-result').forEach(function(item) {
                                item.addEventListener('click', function() {
                                    document.getElementById('title').value = this
                                        .dataset.title;
                                    document.getElementById('author').value = this
                                        .dataset.author;

                                    const languageMap = {
                                        'en': 'English',
                                        'id': 'Indonesian',
                                        'ind': 'Indonesian'
                                    };
                                    const language = languageMap[this.dataset.language
                                            .toLowerCase()] || this.dataset.language ||
                                        'English';
                                    document.getElementById('language').value =
                                        language;

                                    document.getElementById('pages').value = this
                                        .dataset.pages;
                                    document.getElementById('isbn').value = this.dataset
                                        .isbn;
                                    document.getElementById('cover_url').value = this
                                        .dataset.coverUrl;

                                    // Show auto-fill indicator
                                    const indicator = document.getElementById(
                                        'auto-fill-indicator');
                                    indicator.classList.remove('d-none');
                                    indicator.classList.add('fade-in');

                                    // Smooth scroll to form
                                    document.getElementById('title').scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });

                                    // Highlight the filled fields
                                    ['title', 'author', 'language', 'pages', 'isbn',
                                        'cover_url'
                                    ].forEach(function(fieldId) {
                                        const field = document.getElementById(
                                            fieldId);
                                        field.classList.add('border-success');
                                        setTimeout(function() {
                                            field.classList.remove(
                                                'border-success');
                                        }, 2000);
                                    });

                                    resultsDiv.innerHTML = '';
                                    document.getElementById('search-query').value = '';
                                });
                            });
                        } else {
                            resultsDiv.innerHTML =
                                '<div class="alert alert-light text-dark"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-circle me-2" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/></svg>No books found. Try a different search term.</div>';
                        }
                    })
                    .catch(error => {
                        spinner.classList.add('d-none');
                        resultsDiv.innerHTML =
                            '<div class="alert alert-danger">Error searching for books. Please try again.</div>';
                        console.error('Error:', error);
                    });
            }, 300);
        });

        document.getElementById('search-query').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('search-btn').click();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            togglePurchaseFields();

            // Star Rating functionality
            const starContainer = document.getElementById('star-rating');
            if (starContainer) {
                const stars = starContainer.querySelectorAll('.star');
                const ratingInput = document.getElementById('rating');

                function updateStars(rating) {
                    stars.forEach(star => {
                        const val = parseInt(star.getAttribute('data-value'));
                        const svg = star.querySelector('svg');
                        if (val <= rating) {
                            svg.innerHTML = '<path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>';
                            star.style.color = '#ffc107';
                        } else {
                            svg.innerHTML = '<path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957-2.913-2.835 4.022-.58 1.795-3.647 1.795 3.647 4.022.58-2.913 2.835.694 3.957-3.686-1.894z"/>';
                            star.style.color = '#444';
                        }
                    });
                }

                // Initialize with old/existing value
                if (ratingInput.value) {
                    updateStars(parseInt(ratingInput.value));
                }

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const val = parseInt(this.getAttribute('data-value'));
                        ratingInput.value = val;
                        updateStars(val);
                    });

                    star.addEventListener('mouseenter', function() {
                        const val = parseInt(this.getAttribute('data-value'));
                        updateStars(val);
                    });
                });

                starContainer.addEventListener('mouseleave', function() {
                    const currentRating = ratingInput.value ? parseInt(ratingInput.value) : 0;
                    updateStars(currentRating);
                });
            }
        });
    </script>
@endpush
