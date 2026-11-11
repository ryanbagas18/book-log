@extends('layouts.app')

@section('title', 'Rent ' . $book->title)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('library.index') }}" class="text-decoration-none">Ryan Library</a></li>
        <li class="breadcrumb-item"><a href="{{ route('library.show', $book) }}" class="text-decoration-none">{{ $book->title }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rent</li>
    </ol>
</nav>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                @if ($book->cover_image_url)
                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }}" class="img-fluid rounded mb-3">
                @endif
                <h5 class="fw-bold">{{ $book->title }}</h5>
                <p class="text-muted mb-1">{{ $book->author }}</p>
                <p class="text-muted small mb-0">{{ $book->genre?->name }}</p>
                <hr>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Per day</span>
                    <span class="fw-bold text-primary" id="daily-rate" data-rate="{{ $book->daily_price }}">
                        {{ formatRupiah($book->daily_price) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Deposit</span>
                    <span class="fw-bold" id="deposit-amount" data-amount="{{ $book->deposit_price }}">
                        {{ formatRupiah($book->deposit_price) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h1 class="h4 fw-bold mb-1">Rental booking</h1>
                <p class="text-muted mb-4">Fill in the details and upload your transfer proof.</p>

                @if ($bank['account_number'])
                    <div class="alert alert-secondary py-2">
                        <strong>Bank transfer to:</strong>
                        {{ $bank['name'] }} — {{ $bank['account_number'] }}
                        @if ($bank['account_holder']) ({{ $bank['account_holder'] }}) @endif
                        <div class="small mt-1">Total to transfer = rental fee + deposit (see breakdown below).</div>
                    </div>
                @else
                    <div class="alert alert-warning py-2">
                        Bank details are not configured yet. Contact the owner before paying.
                    </div>
                @endif

                <form method="POST" action="{{ route('library.rent.store', $book) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Fulfillment method</label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fulfillment_method"
                                    id="fulfillment_delivery" value="delivery"
                                    @checked(old('fulfillment_method', 'delivery') === 'delivery') required>
                                <label class="form-check-label" for="fulfillment_delivery">Delivery</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="fulfillment_method"
                                    id="fulfillment_pickup" value="pickup"
                                    @checked(old('fulfillment_method') === 'pickup')>
                                <label class="form-check-label" for="fulfillment_pickup">Pickup</label>
                            </div>
                        </div>
                        @error('fulfillment_method')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Delivery is arranged via WhatsApp after confirmation. Pickup address is shared on approval.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start date</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                id="start_date" name="start_date" min="{{ now()->toDateString() }}"
                                value="{{ old('start_date') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End date</label>
                            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                id="end_date" name="end_date" min="{{ now()->toDateString() }}"
                                value="{{ old('end_date') }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum {{ $maxDays }} days, counted inclusively.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="proof_image" class="form-label">Transfer proof</label>
                        <input type="file" class="form-control @error('proof_image') is-invalid @enderror"
                            id="proof_image" name="proof_image" accept="image/jpeg,image/png,image/webp" required>
                        @error('proof_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">JPG, PNG or WebP screenshot of the transfer, max 5 MB.</div>
                    </div>

                    <div class="card bg-transparent border mb-4" id="price-preview">
                        <div class="card-body py-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Rental fee (<span id="preview-days">-</span> days)</span>
                                <span id="preview-fee">-</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Deposit</span>
                                <span id="preview-deposit">-</span>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total transfer</span>
                                <span class="text-primary" id="preview-total">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input @error('terms_accepted') is-invalid @enderror" type="checkbox"
                            id="terms_accepted" name="terms_accepted" value="1" required>
                        <label class="form-check-label" for="terms_accepted">
                            I agree to the <a href="{{ route('terms') }}" target="_blank">rental terms</a>
                        </label>
                        @error('terms_accepted')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary fw-bold">Submit booking</button>
                    <a href="{{ route('library.show', $book) }}" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const dailyRate = parseInt(document.getElementById('daily-rate').dataset.rate, 10);
    const deposit = parseInt(document.getElementById('deposit-amount').dataset.amount, 10);
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');

    function update() {
        const start = startInput.value ? new Date(startInput.value) : null;
        const end = endInput.value ? new Date(endInput.value) : null;
        const daysEl = document.getElementById('preview-days');
        const feeEl = document.getElementById('preview-fee');
        const depEl = document.getElementById('preview-deposit');
        const totalEl = document.getElementById('preview-total');

        if (!start || !end || end < start) {
            daysEl.textContent = '-';
            feeEl.textContent = '-';
            depEl.textContent = fmt(deposit);
            totalEl.textContent = '-';
            return;
        }

        const days = Math.round((end - start) / 86400000) + 1;
        const fee = dailyRate * days;
        daysEl.textContent = days;
        feeEl.textContent = fmt(fee);
        depEl.textContent = fmt(deposit);
        totalEl.textContent = fmt(fee + deposit);
    }

    startInput.addEventListener('change', update);
    endInput.addEventListener('change', update);
    update();
})();
</script>
@endpush
