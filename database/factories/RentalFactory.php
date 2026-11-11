<?php

namespace Database\Factories;

use App\Models\LibraryBook;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('tomorrow', '+20 days');
        $days = fake()->numberBetween(1, 7);
        $end = (clone $start)->modify('+' . ($days - 1) . ' days');
        $dailyPrice = fake()->numberBetween(2, 10) * 1000;
        $deposit = fake()->numberBetween(5, 15) * 10000;
        $fee = $dailyPrice * $days;

        return [
            'user_id' => User::factory(),
            'library_book_id' => LibraryBook::factory(),
            'fulfillment_method' => fake()->randomElement(['delivery', 'pickup']),
            'recipient_name' => fake()->name(),
            'recipient_phone' => '+628' . fake()->numerify('##########'),
            'recipient_address' => fake()->optional(0.7)->address(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
            'days' => $days,
            'daily_price' => $dailyPrice,
            'deposit' => $deposit,
            'rental_fee' => $fee,
            'total_amount' => $fee + $deposit,
            'status' => 'pending_verification',
            'terms_accepted_at' => now(),
        ];
    }
}
