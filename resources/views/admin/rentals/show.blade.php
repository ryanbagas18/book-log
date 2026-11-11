@extends('layouts.admin')

@section('title', 'Rental #' . $rental->id)

@section('admin')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.rentals.index') }}" class="text-decoration-none">Rentals</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rental #{{ $rental->id }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1">{{ $rental->libraryBook->title }}</h1>
                        <p class="text-muted mb-0">
                            {{ $rental->libraryBook->author }}
                            @if ($rental->libraryBook->genre) — {{ $rental->libraryBook->genre->name }} @endif
                        </p>
                    </div>
                    <span class="badge {{ $rental->status_badge_class }} fs-6">{{ $rental->status_label }}</span>
                </div>
                @if ($rental->isOverdue())
                    <div class="alert alert-danger py-2 mb-3">
                        Overdue by {{ $rental->daysLate() }} day(s). Was due {{ $rental->end_date->format('d M Y') }}.
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Customer</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr><td class="text-muted" style="width:40%;">Name</td><td>{{ $rental->user->name }}</td></tr>
                                <tr><td class="text-muted">Email</td><td>{{ $rental->user->email }}</td></tr>
                                <tr><td class="text-muted">Phone</td><td>{{ $rental->recipient_phone }}</td></tr>
                                @if ($rental->recipient_address)
                                    <tr><td class="text-muted">Address</td><td>{{ $rental->recipient_address }}</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Schedule</h6>
                        <table class="table table-sm">
                            <tbody>
                                <tr><td class="text-muted" style="width:40%;">Period</td>
                                    <td>{{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }} ({{ $rental->days }} days)</td></tr>
                                <tr><td class="text-muted">Fulfillment</td><td>{{ ucfirst($rental->fulfillment_method) }}</td></tr>
                                <tr><td class="text-muted">Approved</td><td>{{ $rental->approved_at?->format('d M Y H:i') ?? '—' }}</td></tr>
                                <tr><td class="text-muted">Handed over</td><td>{{ $rental->handed_over_at?->format('d M Y H:i') ?? '—' }}</td></tr>
                                <tr><td class="text-muted">Returned</td><td>{{ $rental->returned_at?->format('d M Y H:i') ?? '—' }}</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($rental->payment)
                    <h6 class="fw-bold mt-3">Payment</h6>
                    <table class="table table-sm">
                        <tbody>
                            <tr><td class="text-muted" style="width:40%;">Amount</td><td>{{ formatRupiah($rental->payment->amount) }}</td></tr>
                            <tr><td class="text-muted">Status</td>
                                <td><span class="badge {{ $rental->payment->status === 'verified' ? 'bg-success' : ($rental->payment->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($rental->payment->status) }}</span></td></tr>
                            @if ($rental->payment->rejection_reason)
                                <tr><td class="text-muted">Rejection reason</td><td>{{ $rental->payment->rejection_reason }}</td></tr>
                            @endif
                            @if ($rental->payment->verifier)
                                <tr><td class="text-muted">Verified by</td><td>{{ $rental->payment->verifier->name }}</td></tr>
                            @endif
                        </tbody>
                    </table>
                    <a href="{{ route('rentals.proof', $rental) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        View proof image
                    </a>
                @endif

                @if ($rental->rejection_reason)
                    <div class="alert alert-danger mt-3 mb-0">
                        <strong>Rejection reason:</strong> {{ $rental->rejection_reason }}
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Actions</div>
            <div class="card-body">
                @if ($rental->status === 'pending_verification')
                    <div class="d-flex gap-2 flex-wrap">
                        <form method="POST" action="{{ route('admin.rentals.approve', $rental) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve & verify payment</button>
                        </form>
                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                            Reject
                        </button>
                    </div>
                    <div class="collapse mt-3" id="rejectForm">
                        <form method="POST" action="{{ route('admin.rentals.reject', $rental) }}">
                            @csrf
                            <div class="mb-2">
                                <label for="rejection_reason" class="form-label">Rejection reason</label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason"
                                    rows="2" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Confirm rejection</button>
                        </form>
                    </div>
                @elseif ($rental->status === 'approved')
                    <form method="POST" action="{{ route('admin.rentals.hand-over', $rental) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Mark as handed over</button>
                    </form>
                @elseif ($rental->status === 'on_rent')
                    <form method="POST" action="{{ route('admin.rentals.return', $rental) }}">
                        @csrf
                        <div class="mb-2" style="max-width: 280px;">
                            <label for="deposit_deduction" class="form-label">
                                Deposit deduction (Rp, max {{ formatRupiah($rental->deposit) }})
                            </label>
                            <input type="number" class="form-control @error('deposit_deduction') is-invalid @enderror"
                                id="deposit_deduction" name="deposit_deduction" min="0"
                                max="{{ $rental->deposit }}" value="0" required>
                            @error('deposit_deduction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">For late returns or damage. Leave 0 for a full refund.</div>
                        </div>
                        <button type="submit" class="btn btn-success">Mark as returned</button>
                    </form>
                @elseif ($rental->status === 'returned' && $rental->deposit_refund_status === 'pending')
                    <form method="POST" action="{{ route('admin.rentals.refund-deposit', $rental) }}">
                        @csrf
                        <p class="text-muted small">
                            Refund due: {{ formatRupiah($rental->deposit - $rental->deposit_deduction) }}.
                            Transfer manually, then mark as refunded.
                        </p>
                        <button type="submit" class="btn btn-success">Mark deposit refunded</button>
                    </form>
                @else
                    <p class="text-muted mb-0">No actions available for this status.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header fw-bold">Price breakdown</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ formatRupiah($rental->daily_price) }} x {{ $rental->days }} days</span>
                    <span>{{ formatRupiah($rental->rental_fee) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Deposit</span>
                    <span>{{ formatRupiah($rental->deposit) }}</span>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span class="text-primary">{{ formatRupiah($rental->total_amount) }}</span>
                </div>
                @if ($rental->deposit_deduction > 0)
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Deposit deduction</span>
                        <span class="text-danger">-{{ formatRupiah($rental->deposit_deduction) }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Refund status</span>
                        <span class="badge {{ $rental->deposit_refund_status === 'refunded' ? 'bg-success' : ($rental->deposit_refund_status === 'forfeited' ? 'bg-danger' : 'bg-warning text-dark') }}">
                            {{ ucfirst($rental->deposit_refund_status) }}
                        </span>
                    </div>
                    @if ($rental->deposit_refunded_at)
                        <div class="text-muted small mt-1">Refunded {{ $rental->deposit_refunded_at->format('d M Y H:i') }}</div>
                    @endif
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="btn btn-success w-100">
                    Message customer on WhatsApp
                </a>
                <div class="text-muted small mt-2 text-center">Opens wa.me with rental details pre-filled.</div>
            </div>
        </div>
    </div>
</div>
@endsection
