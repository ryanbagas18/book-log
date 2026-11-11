@extends('layouts.admin')

@section('title', 'Add Library Book')

@section('admin')
<div class="mb-4">
    <h1 class="fw-bold">Add Library Book</h1>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.library-books.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.library-books._form')
        </form>
    </div>
</div>
@endsection
