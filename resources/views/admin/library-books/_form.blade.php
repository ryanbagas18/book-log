<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror"
                id="title" name="title" value="{{ old('title', $book->title ?? '') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" class="form-control @error('author') is-invalid @enderror"
                    id="author" name="author" value="{{ old('author', $book->author ?? '') }}">
                @error('author')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="isbn" class="form-label">ISBN</label>
                <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                    id="isbn" name="isbn" value="{{ old('isbn', $book->isbn ?? '') }}" maxlength="20">
                @error('isbn')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="genre_id" class="form-label">Genre</label>
            <select class="form-select @error('genre_id') is-invalid @enderror" id="genre_id" name="genre_id" required>
                <option value="">Select a genre</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}"
                        @selected(old('genre_id', $book->genre_id ?? '') == $genre->id)>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
            @error('genre_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="synopsis" class="form-label">Synopsis</label>
            <textarea class="form-control @error('synopsis') is-invalid @enderror" id="synopsis"
                name="synopsis" rows="4">{{ old('synopsis', $book->synopsis ?? '') }}</textarea>
            @error('synopsis')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="goodreads_url" class="form-label">Goodreads URL</label>
            <input type="url" class="form-control @error('goodreads_url') is-invalid @enderror"
                id="goodreads_url" name="goodreads_url"
                value="{{ old('goodreads_url', $book->goodreads_url ?? '') }}">
            @error('goodreads_url')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="language" class="form-label">Language</label>
                <input type="text" class="form-control @error('language') is-invalid @enderror"
                    id="language" name="language" value="{{ old('language', $book->language ?? 'Indonesian') }}" required>
                @error('language')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="pages" class="form-label">Pages</label>
                <input type="number" class="form-control @error('pages') is-invalid @enderror"
                    id="pages" name="pages" min="1" value="{{ old('pages', $book->pages ?? '') }}">
                @error('pages')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover image</label>
            <input type="file" class="form-control @error('cover_image') is-invalid @enderror"
                id="cover_image" name="cover_image" accept="image/jpeg,image/png,image/webp">
            @error('cover_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">JPG, PNG or WebP, max 2 MB.</div>
            @if (!empty($book?->cover_image_url))
                <img src="{{ $book->cover_image_url }}" alt="Current cover" class="mt-2 rounded"
                    style="max-height: 120px;">
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="daily_price" class="form-label">Daily price (Rp)</label>
            <input type="number" class="form-control @error('daily_price') is-invalid @enderror"
                id="daily_price" name="daily_price" min="1"
                value="{{ old('daily_price', $book->daily_price ?? '') }}" required>
            @error('daily_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deposit_price" class="form-label">Deposit (Rp)</label>
            <input type="number" class="form-control @error('deposit_price') is-invalid @enderror"
                id="deposit_price" name="deposit_price" min="0"
                value="{{ old('deposit_price', $book->deposit_price ?? 0) }}">
            @error('deposit_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock</label>
            <input type="number" class="form-control @error('stock') is-invalid @enderror"
                id="stock" name="stock" min="0" value="{{ old('stock', $book->stock ?? 1) }}">
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                @checked(old('is_active', $book->is_active ?? true))>
            <label class="form-check-label" for="is_active">Active (visible in catalog)</label>
        </div>

        <button type="submit" class="btn btn-primary fw-bold w-100">Save</button>
        <a href="{{ route('admin.library-books.index') }}" class="btn btn-outline-secondary w-100 mt-2">Cancel</a>
    </div>
</div>
