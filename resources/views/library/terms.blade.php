@extends('layouts.app')

@section('title', 'Rental Terms')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <h1 class="fw-bold mb-4">Rental Terms</h1>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="fw-bold">1. Rental period and return</h5>
                <p class="text-muted">
                    Rentals run for the number of days you select at booking, counted inclusively
                    (start date through end date). The book must be returned by the end of the
                    return date. Late returns are handled manually by the owner; contact us on
                    WhatsApp as soon as you know you will be late.
                </p>

                <h5 class="fw-bold">2. Deposit</h5>
                <p class="text-muted">
                    A refundable deposit is charged together with the rental fee. The deposit is
                    returned after the book is checked and found in good condition. There are no
                    automatic late fees; any deduction for lateness or damage is agreed with you
                    manually before the refund is sent.
                </p>

                <h5 class="fw-bold">3. Lost or damaged books</h5>
                <p class="text-muted">
                    If a book is lost or returned damaged beyond normal wear, the deposit may be
                    forfeited partially or fully to cover the replacement cost. The owner will
                    explain any deduction before processing the refund.
                </p>

                <h5 class="fw-bold">4. Deposit refund process</h5>
                <p class="text-muted">
                    Refunds are sent manually by bank transfer after the book is returned and
                    inspected. You will be contacted via WhatsApp with the transfer details.
                </p>

                <h5 class="fw-bold">5. Payment and cancellation</h5>
                <p class="text-muted">
                    Payment is by manual bank transfer with a photo of the transfer proof. Your
                    booking is confirmed only after the owner verifies the payment. You may cancel
                    a booking only while it is still pending verification; once approved, contact
                    the owner on WhatsApp to arrange changes.
                </p>

                <h5 class="fw-bold">6. Delivery and pickup</h5>
                <p class="text-muted">
                    Delivery is arranged and paid separately through WhatsApp. Pickup is available
                    at the library location; details are provided after booking confirmation.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
