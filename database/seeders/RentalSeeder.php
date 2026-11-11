<?php

namespace Database\Seeders;

use App\Models\LibraryBook;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('email', 'customer.complete@example.test')->first();
        $admin = User::where('email', 'admin@ryanlibrary.test')->first();

        if (! $customer || ! $admin) {
            return;
        }

        $books = LibraryBook::where('is_active', true)->where('stock', '>', 0)->get();

        if ($books->isEmpty()) {
            return;
        }

        $samples = [
            [
                'status' => 'pending_verification',
                'start' => now()->addDays(2),
                'days' => 3,
                'payment' => 'pending',
            ],
            [
                'status' => 'approved',
                'start' => now()->addDays(5),
                'days' => 7,
                'approved_at' => now()->subDay(),
                'payment' => 'verified',
            ],
            [
                'status' => 'on_rent',
                'start' => now()->subDays(12),
                'days' => 7,
                'approved_at' => now()->subDays(13),
                'handed_over_at' => now()->subDays(12),
                'payment' => 'verified',
                // ends 5 days ago -> overdue
            ],
            [
                'status' => 'returned',
                'start' => now()->subDays(20),
                'days' => 5,
                'approved_at' => now()->subDays(21),
                'handed_over_at' => now()->subDays(20),
                'returned_at' => now()->subDays(15),
                'deposit_deduction' => 10000,
                'deposit_refund_status' => 'refunded',
                'deposit_refunded_at' => now()->subDays(14),
                'payment' => 'verified',
            ],
            [
                'status' => 'rejected',
                'start' => now()->addDays(3),
                'days' => 4,
                'rejection_reason' => 'Transfer proof was unreadable.',
                'payment' => 'rejected',
            ],
            [
                'status' => 'cancelled',
                'start' => now()->addDays(4),
                'days' => 2,
                'payment' => 'pending',
            ],
        ];

        foreach ($samples as $i => $sample) {
            $book = $books[$i % $books->count()];
            $days = $sample['days'];
            $start = $sample['start'];
            $end = $start->copy()->addDays($days - 1);
            $fee = $book->daily_price * $days;
            $total = $fee + $book->deposit_price;

            $rental = Rental::create([
                'user_id' => $customer->id,
                'library_book_id' => $book->id,
                'fulfillment_method' => $i % 2 === 0 ? 'delivery' : 'pickup',
                'recipient_name' => $customer->name,
                'recipient_phone' => $customer->phone,
                'recipient_address' => $i % 2 === 0 ? $customer->address : null,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'days' => $days,
                'daily_price' => $book->daily_price,
                'deposit' => $book->deposit_price,
                'rental_fee' => $fee,
                'total_amount' => $total,
                'status' => $sample['status'],
                'rejection_reason' => $sample['rejection_reason'] ?? null,
                'terms_accepted_at' => $start->copy()->subDays(3),
                'approved_at' => $sample['approved_at'] ?? null,
                'handed_over_at' => $sample['handed_over_at'] ?? null,
                'returned_at' => $sample['returned_at'] ?? null,
                'deposit_deduction' => $sample['deposit_deduction'] ?? 0,
                'deposit_refund_status' => $sample['deposit_refund_status'] ?? 'none',
                'deposit_refunded_at' => $sample['deposit_refunded_at'] ?? null,
            ]);

            $rental->payment()->create([
                'amount' => $total,
                'proof_path' => 'payment-proofs/seeded-' . $rental->id . '.jpg',
                'status' => $sample['payment'],
                'verified_by' => ($sample['payment'] === 'verified') ? $admin->id : null,
                'verified_at' => ($sample['payment'] === 'verified') ? ($sample['approved_at'] ?? now()) : null,
                'rejection_reason' => ($sample['payment'] === 'rejected') ? ($sample['rejection_reason'] ?? null) : null,
            ]);
        }
    }
}
