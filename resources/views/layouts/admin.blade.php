@extends('layouts.app')

@section('content')
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.rentals.*') ? 'active' : '' }}"
            href="{{ route('admin.rentals.index') }}">Rentals</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}"
            href="{{ route('admin.genres.index') }}">Genres</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.library-books.*') ? 'active' : '' }}"
            href="{{ route('admin.library-books.index') }}">Library Books</a>
    </li>
</ul>

@yield('admin')
@endsection
