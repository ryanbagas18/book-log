<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titles = [
            'Laskar Pelangi', 'Bumi Manusia', 'Negeri 5 Menara', 'Saman', 'Supernova',
            'The Alchemist', 'Atomic Habits', 'Rich Dad Poor Dad', 'Ikigai', 'Thinking, Fast and Slow',
            'Deep Work', 'The Psychology of Money', 'Sapiens', 'Zero to One', 'Start with Why',
            'How to Win Friends', 'The Power of Now', 'Mindset', 'Grit', 'Educated'
        ];

        $authors = [
            'Andrea Hirata', 'Pramoedya Ananta Toer', 'A. Fuadi', 'Ayu Utami', 'Dee Lestari',
            'Paulo Coelho', 'James Clear', 'Robert Kiyosaki', 'Héctor García', 'Daniel Kahneman',
            'Cal Newport', 'Morgan Housel', 'Yuval Noah Harari', 'Peter Thiel', 'Simon Sinek',
            'Dale Carnegie', 'Eckhart Tolle', 'Carol Dweck', 'Angela Duckworth', 'Tara Westover'
        ];

        $genres = ['Fiction', 'Non-Fiction', 'Self-Help', 'Biography', 'History', 'Philosophy', 'Science', 'Business'];
        $languages = ['Indonesian', 'English', 'Other'];
        $statuses = ['to_read', 'reading', 'finished', 'dropped'];
        $ownerships = ['owned', 'skimmed', 'borrowed', 'wishlist'];
        $conditions = ['new', 'preloved'];
        $purchasePlaces = ['Tokopedia', 'Shopee', 'Togamas', 'Gramedia', 'Mochi Pochi', 'Gifted/Free'];

        $ownership = fake()->randomElement($ownerships);
        $condition = $ownership === 'owned' ? fake()->randomElement($conditions) : null;
        $purchasePlace = $ownership === 'owned' ? fake()->randomElement($purchasePlaces) : null;
        $purchaseDate = $ownership === 'owned' ? fake()->dateBetween('-2 years', 'now') : null;
        $price = $ownership === 'owned' && $purchasePlace !== 'Gifted/Free' ? fake()->numberBetween(50000, 500000) : 0;

        $status = fake()->randomElement($statuses);
        $startedAt = in_array($status, ['reading', 'finished', 'dropped']) ? fake()->dateBetween('-1 year', 'now') : null;
        $finishedAt = $status === 'finished' ? fake()->optional(0.8)->dateBetween($startedAt ?? '-1 year', 'now') : null;

        return [
            'title' => fake()->randomElement($titles),
            'author' => fake()->randomElement($authors),
            'isbn' => fake()->optional(0.7)->isbn13(),
            'genre' => fake()->randomElement($genres),
            'language' => fake()->randomElement($languages),
            'pages' => fake()->optional(0.8)->numberBetween(100, 800),
            'status' => $status,
            'rating' => $status === 'finished' ? fake()->numberBetween(1, 5) : null,
            'started_at' => $startedAt,
            'finished_at' => $finishedAt,
            'ownership' => $ownership,
            'purchase_place' => $purchasePlace,
            'condition' => $condition,
            'purchase_date' => $purchaseDate,
            'price' => $price,
            'cover_image' => null,
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }
}
