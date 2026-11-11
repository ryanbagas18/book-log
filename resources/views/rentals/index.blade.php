@extends('layouts.app')

@section('title', 'My Rentals')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="fw-bold mb-1">My Rentals</h1>
        <p class="text-muted mb-0">Your Ryan Library bookings.</p>
    </div>
    <a href="{{ route('library.index') }}" class="btn btn-primary fw-bold">Browse Library</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Period</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rentals as $rental)
                    <tr>
                        <td>
                            <a href="{{ route('rentals.show', $rental) }}" class="text-decoration-none fw-bold">
                                {{ $rental->libraryBook->title }}
                            </a>
                            <div class="text-muted small">{{ $rental->libraryBook->author }}</div>
                        </td>
                        <td>{{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}</td>
                        <td>{{ formatRupiah($rental->total_amount) }}</td>
                        <td><span class="badge {{ $rental->status_badge_class }}">{{ $rental->status_label }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('rentals.show', $rental) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No rentals yet. <a href="{{ route('library.index') }}">Browse the library</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $rentals->links() }}
</div>
@endsection
