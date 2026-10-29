@extends('layouts.app')

@section('title', 'Add Book')

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('books.index') }}" class="text-decoration-none">My Books</a></li>
        <li class="breadcrumb-item active" aria-current="page">Add New Book</li>
    </ol>
</nav>

<div class="mb-4">
    <h1 class="fw-bold">Add New Book</h1>
    <p class="text-muted">Fill in the details below or search from API to auto-fill</p>
</div>

@php
    $action = route('books.store');
@endphp

@include('books._form')
@endsection
