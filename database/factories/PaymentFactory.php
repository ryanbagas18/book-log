<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'rental_id' => Rental::factory(),
            'amount' => fake()->numberBetween(5, 20) * 10000,
            'proof_path' => 'payment-proofs/' . fake()->uuid() . '.jpg',
            'status' => 'pending',
        ];
    }
}
