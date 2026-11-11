@extends('layouts.app')

@section('title', 'Rental #' . $rental->id)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('rentals.index') }}" class="text-decoration-none">My Rentals</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rental #{{ $rental->id }}</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="h4 fw-bold mb-1">{{ $rental->libraryBook->title }}</h1>
                        <p class="text-muted mb-0">{{ $rental->libraryBook->author }}</p>
                    </div>
                    <span class="badge {{ $rental->status_badge_class }} fs-6">{{ $rental->status_label }}</span>
                </div>

                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted" style="width: 40%;">Rental period</td>
                            <td>{{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}
                                ({{ $rental->days }} days)</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fulfillment</td>
                            <td>{{ ucfirst($rental->fulfillment_method) }}</td>
                        </tr>
                        @if ($rental->fulfillment_method === 'delivery')
                            <tr>
                                <td class="text-muted">Deliver to</td>
                                <td>{{ $rental->recipient_name }}, {{ $rental->recipient_phone }}<br>
                                    {{ $rental->recipient_address }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-muted">Recipient</td>
                                <td>{{ $rental->recipient_name }}, {{ $rental->recipient_phone }}</td>
                            </tr>
                        @endif
                        @if ($rental->approved_at)
                            <tr>
                                <td class="text-muted">Approved at</td>
                                <td>{{ $rental->approved_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endif
                        @if ($rental->handed_over_at)
                            <tr>
                                <td class="text-muted">Handed over</td>
                                <td>{{ $rental->handed_over_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endif
                        @if ($rental->returned_at)
                            <tr>
                                <td class="text-muted">Returned</td>
                                <td>{{ $rental->returned_at->format('d M Y H:i') }}</td>
                            </tr>
                        @endif
                        @if ($rental->rejection_reason)
                            <tr>
                                <td class="text-muted">Rejection reason</td>
                                <td>{{ $rental->rejection_reason }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Payment</div>
            <div class="card-body">
                @if ($rental->payment)
                    <table class="table table-sm mb-3">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width: 40%;">Amount</td>
                                <td>{{ formatRupiah($rental->payment->amount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Payment status</td>
                                <td>
                                    <span class="badge {{ $rental->payment->status === 'verified' ? 'bg-success' : ($rental->payment->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ ucfirst($rental->payment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @if ($rental->payment->rejection_reason)
                                <tr>
                                    <td class="text-muted">Reason</td>
                                    <td>{{ $rental->payment->rejection_reason }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <a href="{{ route('rentals.proof', $rental) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        View transfer proof
                    </a>
                @else
                    <p class="text-muted mb-0">No payment record.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
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
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Deposit refund</div>
            <div class="card-body">
                @if ($rental->deposit_deduction > 0)
                    <p class="text-muted small mb-2">Deduction: {{ formatRupiah($rental->deposit_deduction) }}</p>
                @endif
                <p class="mb-0">
                    @switch($rental->deposit_refund_status)
                        @case('pending')
                            <span class="badge bg-warning text-dark">Refund pending</span>
                            <div class="text-muted small mt-1">The owner will transfer the refund manually.</div>
                            @break
                        @case('refunded')
                            <span class="badge bg-success">Refunded</span>
                            @if ($rental->deposit_refunded_at)
                                <div class="text-muted small mt-1">{{ $rental->deposit_refunded_at->format('d M Y H:i') }}</div>
                            @endif
                            @break
                        @case('forfeited')
                            <span class="badge bg-danger">Forfeited</span>
                            @break
                        @default
                            <span class="badge bg-secondary">Not applicable yet</span>
                    @endswitch
                </p>
            </div>
        </div>

        @if ($rental->status === 'pending_verification')
            <form method="POST" action="{{ route('rentals.cancel', $rental) }}" class="mt-3"
                onsubmit="return confirm('Cancel this rental? This cannot be undone.');">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">Cancel booking</button>
            </form>
        @endif
    </div>
</div>
@endsection
