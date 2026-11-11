@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin')
<div class="mb-4">
    <h1 class="fw-bold">Admin dashboard</h1>
    <p class="text-muted">Ryan's Library administration.</p>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Pending verification</div>
                <div class="fw-bold fs-3">{{ $statusCounts['pending_verification'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">On rent</div>
                <div class="fw-bold fs-3">{{ $statusCounts['on_rent'] ?? 0 }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Deposits held</div>
                <div class="fw-bold fs-5">{{ formatRupiah($depositsHeld) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Pending deposit refunds</div>
                <div class="fw-bold fs-5">{{ formatRupiah($pendingRefunds) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold">Pending verification</span>
                <a href="{{ route('admin.rentals.index', ['status' => 'pending_verification']) }}"
                    class="btn btn-sm btn-outline-secondary">View all</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <tbody>
                        @forelse ($pendingVerification as $rental)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="text-decoration-none fw-bold">
                                        {{ $rental->libraryBook->title }}
                                    </a>
                                    <div class="text-muted small">{{ $rental->user->name }}</div>
                                </td>
                                <td>{{ $rental->start_date->format('d M') }} - {{ $rental->end_date->format('d M Y') }}</td>
                                <td>{{ formatRupiah($rental->total_amount) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center text-muted py-4">Nothing pending.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Overdue rentals</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Customer</th>
                            <th>Was due</th>
                            <th>Days late</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($overdue as $rental)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.rentals.show', $rental) }}" class="text-decoration-none fw-bold">
                                        {{ $rental->libraryBook->title }}
                                    </a>
                                </td>
                                <td>{{ $rental->user->name }}</td>
                                <td>{{ $rental->end_date->format('d M Y') }}</td>
                                <td><span class="badge bg-danger">{{ $rental->daysLate() }} days</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No overdue rentals.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card">
            <div class="card-header fw-bold">Rentals by status</div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    @foreach ([
                        'pending_verification' => 'Pending verification',
                        'approved' => 'Approved',
                        'on_rent' => 'On rent',
                        'returned' => 'Returned',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ] as $key => $label)
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <a href="{{ route('admin.rentals.index', ['status' => $key]) }}" class="text-decoration-none">
                                {{ $label }}
                            </a>
                            <span class="badge bg-secondary">{{ $statusCounts[$key] ?? 0 }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
