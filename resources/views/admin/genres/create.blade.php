@extends('layouts.admin')

@section('title', 'Add Genre')

@section('admin')
<div class="mb-4">
    <h1 class="fw-bold">Add Genre</h1>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.genres.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                    id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">The slug is generated automatically from the name.</div>
            </div>

            <button type="submit" class="btn btn-primary fw-bold">Save</button>
            <a href="{{ route('admin.genres.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
