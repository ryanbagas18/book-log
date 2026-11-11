@extends('layouts.admin')

@section('title', 'Rentals')

@section('admin')
<div class="mb-4">
    <h1 class="fw-bold">Rentals</h1>
    <p class="text-muted">All rental bookings.</p>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.rentals.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select" style="max-width: 260px;" onchange="this.form.submit()">
                <option value="">All statuses</option>
                @foreach ([
                    'pending_verification' => 'Pending verification',
                    'approved' => 'Approved',
                    'on_rent' => 'On rent',
                    'returned' => 'Returned',
                    'rejected' => 'Rejected',
                    'cancelled' => 'Cancelled',
                ] as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Book</th>
                    <th>Customer</th>
                    <th>Period</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rentals as $rental)
                    <tr>
                        <td>{{ $rental->id }}</td>
                        <td>{{ $rental->libraryBook->title }}</td>
                        <td>{{ $rental->user->name }}</td>
                        <td>{{ $rental->start_date->format('d M') }} - {{ $rental->end_date->format('d M Y') }}</td>
                        <td>{{ formatRupiah($rental->total_amount) }}</td>
                        <td>
                            <span class="badge {{ $rental->status_badge_class }}">{{ $rental->status_label }}</span>
                            @if ($rental->isOverdue())
                                <span class="badge bg-danger">{{ $rental->daysLate() }}d late</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.rentals.show', $rental) }}" class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No rentals found.</td>
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
