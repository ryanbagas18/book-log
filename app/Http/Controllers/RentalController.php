<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Models\LibraryBook;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $rentals = $request->user()->rentals()
            ->with('libraryBook.genre')
            ->latest()
            ->paginate(15);

        return view('rentals.index', compact('rentals'));
    }

    public function create(LibraryBook $libraryBook)
    {
        abort_unless($libraryBook->is_active && $libraryBook->stock > 0, 404);

        return view('rentals.create', [
            'book' => $libraryBook->load('genre'),
            'bank' => [
                'name' => config('rental.bank_name'),
                'account_number' => config('rental.bank_account_number'),
                'account_holder' => config('rental.bank_account_holder'),
            ],
            'maxDays' => config('rental.max_days'),
        ]);
    }

    public function store(StoreRentalRequest $request, LibraryBook $libraryBook)
    {
        abort_unless($libraryBook->is_active, 404);

        $days = $request->rentalDays();
        $dailyPrice = $libraryBook->daily_price;
        $deposit = $libraryBook->deposit_price;
        $rentalFee = $dailyPrice * $days;
        $totalAmount = $rentalFee + $deposit;

        $user = $request->user();
        $isDelivery = $request->input('fulfillment_method') === 'delivery';

        $rental = DB::transaction(function () use ($request, $libraryBook, $user, $isDelivery, $days, $dailyPrice, $deposit, $rentalFee, $totalAmount) {
            // Lock the book row so two customers cannot book the last copy at once.
            $book = LibraryBook::whereKey($libraryBook->id)->lockForUpdate()->firstOrFail();

            if (! $book->is_active || $book->stock < 1) {
                throw ValidationException::withMessages([
                    'book' => 'This book is no longer available for rent.',
                ]);
            }

            $rental = Rental::create([
                'user_id' => $user->id,
                'library_book_id' => $book->id,
                'fulfillment_method' => $request->input('fulfillment_method'),
                'recipient_name' => $user->name,
                'recipient_phone' => $user->phone,
                'recipient_address' => $isDelivery ? $user->address : null,
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'days' => $days,
                'daily_price' => $dailyPrice,
                'deposit' => $deposit,
                'rental_fee' => $rentalFee,
                'total_amount' => $totalAmount,
                'terms_accepted_at' => now(),
            ]);

            $proofPath = $request->file('proof_image')->store('payment-proofs', 'local');

            $rental->payment()->create([
                'amount' => $totalAmount,
                'proof_path' => $proofPath,
                'status' => 'pending',
            ]);

            return $rental;
        });

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Rental request submitted! Your booking is pending payment verification.');
    }

    public function show(Request $request, Rental $rental)
    {
        $this->findOwnedRental($request, $rental);

        return view('rentals.show', [
            'rental' => $rental->load(['libraryBook.genre', 'payment']),
        ]);
    }

    public function cancel(Request $request, Rental $rental)
    {
        $rental = $this->findOwnedRental($request, $rental);

        if ($rental->status !== 'pending_verification') {
            return redirect()->route('rentals.show', $rental)
                ->with('error', 'Only pending bookings can be cancelled.');
        }

        $rental->update(['status' => 'cancelled']);

        return redirect()->route('rentals.index')->with('success', 'Rental cancelled.');
    }

    public function proof(Request $request, Rental $rental)
    {
        $user = $request->user();
        abort_unless($rental->user_id === $user->id || $user->isAdmin(), 403);

        $payment = $rental->payment;
        abort_unless($payment && Storage::disk('local')->exists($payment->proof_path), 404);

        return Storage::disk('local')->response($payment->proof_path);
    }

    private function findOwnedRental(Request $request, Rental $rental): Rental
    {
        return $request->user()->rentals()->findOrFail($rental->getKey());
    }
}
