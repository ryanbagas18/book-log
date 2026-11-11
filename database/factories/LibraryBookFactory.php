<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\LibraryBook;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LibraryBook>
 */
class LibraryBookFactory extends Factory
{
    protected $model = LibraryBook::class;

    public function definition(): array
    {
        return [
            'genre_id' => Genre::factory(),
            'title' => fake()->sentence(4),
            'author' => fake()->name(),
            'isbn' => fake()->optional(0.7)->isbn13(),
            'synopsis' => fake()->optional(0.8)->paragraph(),
            'goodreads_url' => fake()->optional(0.5)->url(),
            'language' => fake()->randomElement(['Indonesian', 'English']),
            'pages' => fake()->optional(0.8)->numberBetween(100, 800),
            'cover_image' => null,
            'deposit_price' => fake()->numberBetween(5, 15) * 10000,
            'daily_price' => fake()->numberBetween(2, 10) * 1000,
            'stock' => fake()->numberBetween(0, 5),
            'is_active' => true,
        ];
    }
}
