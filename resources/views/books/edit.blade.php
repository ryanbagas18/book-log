@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">My Books</a></li>
        <li class="breadcrumb-item"><a href="{{ route('books.show', $book) }}" class="text-decoration-none">{{ $book->title }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>

<div class="mb-4">
    <h1 class="fw-bold">Edit Book</h1>
    <p class="text-muted">Update the book details below</p>
</div>

@php
    $action = route('books.update', $book);
@endphp

@include('books._form')
@endsection
