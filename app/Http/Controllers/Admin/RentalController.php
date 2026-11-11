<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    private const STATUSES = [
        'pending_verification',
        'approved',
        'on_rent',
        'returned',
        'rejected',
        'cancelled',
    ];

    public function index(Request $request)
    {
        $status = $request->query('status');

        $rentals = Rental::with(['user', 'libraryBook'])
            ->when($status && in_array($status, self::STATUSES, true), fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.rentals.index', compact('rentals', 'status'));
    }

    public function show(Rental $rental)
    {
        $rental->load(['user', 'libraryBook.genre', 'payment']);

        return view('admin.rentals.show', [
            'rental' => $rental,
            'whatsappUrl' => $this->whatsappUrl($rental),
        ]);
    }

    public function approve(Rental $rental)
    {
        if ($rental->status !== 'pending_verification') {
            return $this->invalidTransition($rental);
        }

        try {
            DB::transaction(function () use ($rental) {
                // Lock the copy count so concurrent approvals cannot oversell the last copy.
                $book = $rental->libraryBook()->lockForUpdate()->firstOrFail();

                if ($book->stock < 1) {
                    throw new \RuntimeException('Out of stock.');
                }

                $book->decrement('stock');

                $rental->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);

                $rental->payment()->update([
                    'status' => 'verified',
                    'verified_by' => auth()->id(),
                    'verified_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('admin.rentals.show', $rental)
                ->with('error', $e->getMessage());
        }

        return redirect()->route('admin.rentals.show', $rental)->with('success', 'Rental approved.');
    }

    public function reject(Request $request, Rental $rental)
    {
        if ($rental->status !== 'pending_verification') {
            return $this->invalidTransition($rental);
        }

        $data = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($rental, $data) {
            $rental->update([
                'status' => 'rejected',
                'rejection_reason' => $data['rejection_reason'],
            ]);

            $rental->payment()->update([
                'status' => 'rejected',
                'rejection_reason' => $data['rejection_reason'],
            ]);
        });

        return redirect()->route('admin.rentals.show', $rental)->with('success', 'Rental rejected.');
    }

    public function handOver(Rental $rental)
    {
        if ($rental->status !== 'approved') {
            return $this->invalidTransition($rental);
        }

        $rental->update([
            'status' => 'on_rent',
            'handed_over_at' => now(),
        ]);

        return redirect()->route('admin.rentals.show', $rental)->with('success', 'Marked as handed over.');
    }

    public function markReturned(Request $request, Rental $rental)
    {
        if ($rental->status !== 'on_rent') {
            return $this->invalidTransition($rental);
        }

        $data = $request->validate([
            'deposit_deduction' => ['required', 'integer', 'min:0'],
        ]);

        if ($data['deposit_deduction'] > $rental->deposit) {
            return redirect()->route('admin.rentals.show', $rental)
                ->withErrors(['deposit_deduction' => 'Deduction cannot exceed the deposit.']);
        }

        $deduction = $data['deposit_deduction'];
        $refundAmount = $rental->deposit - $deduction;

        DB::transaction(function () use ($rental, $deduction, $refundAmount) {
            $rental->libraryBook()->increment('stock');

            $rental->update([
                'status' => 'returned',
                'returned_at' => now(),
                'deposit_deduction' => $deduction,
                'deposit_refund_status' => $refundAmount > 0 ? 'pending' : 'forfeited',
            ]);
        });

        return redirect()->route('admin.rentals.show', $rental)->with('success', 'Rental marked as returned.');
    }

    public function refundDeposit(Rental $rental)
    {
        if ($rental->status !== 'returned' || $rental->deposit_refund_status !== 'pending') {
            return $this->invalidTransition($rental);
        }

        $rental->update([
            'deposit_refund_status' => 'refunded',
            'deposit_refunded_at' => now(),
        ]);

        return redirect()->route('admin.rentals.show', $rental)->with('success', 'Deposit marked as refunded.');
    }

    private function whatsappUrl(Rental $rental): string
    {
        $phone = preg_replace('/\D/', '', $rental->recipient_phone ?? '');

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        $message = sprintf(
            'Rental #%d: %s (%s - %s), status: %s',
            $rental->id,
            $rental->libraryBook->title,
            $rental->start_date->format('d M Y'),
            $rental->end_date->format('d M Y'),
            $rental->status_label
        );

        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }

    private function invalidTransition(Rental $rental)
    {
        return redirect()->route('admin.rentals.show', $rental)
            ->with('error', 'That action is not allowed for the current status (' . $rental->status_label . ').');
    }
}
