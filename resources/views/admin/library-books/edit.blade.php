@extends('layouts.admin')

@section('title', 'Edit Library Book')

@section('admin')
<div class="mb-4">
    <h1 class="fw-bold">Edit Library Book</h1>
</div>

<div class="alert alert-warning py-2">
    Books with rental history cannot be hard-deleted; deleting only archives them (soft delete).
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.library-books.update', $book) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.library-books._form')
        </form>
    </div>
</div>
@endsection
