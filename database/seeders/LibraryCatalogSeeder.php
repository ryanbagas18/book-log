<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\LibraryBook;
use Illuminate\Database\Seeder;

class LibraryCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $genres = collect([
            'Fiction',
            'Non-Fiction',
            'Self-Improvement',
            'Children',
            'Comics',
            'Religion & Spirituality',
        ])->mapWithKeys(fn ($name) => [$name => Genre::firstOrCreate(['name' => $name])]);

        $books = [
            ['Laskar Pelangi', 'Andrea Hirata', 'Fiction', 'Indonesian', 529, 100000, 5000, 3],
            ['Bumi Manusia', 'Pramoedya Ananta Toer', 'Fiction', 'Indonesian', 535, 120000, 6000, 2],
            ['Negeri 5 Menara', 'A. Fuadi', 'Fiction', 'Indonesian', 403, 90000, 4500, 4],
            ['Saman', 'Ayu Utami', 'Fiction', 'Indonesian', 352, 80000, 4000, 1],
            ['Supernova', 'Dee Lestari', 'Fiction', 'Indonesian', 448, 95000, 5000, 0],
            ['Filosofi Teras', 'Henry Manampiring', 'Self-Improvement', 'Indonesian', 280, 75000, 3500, 5],
            ['Atomic Habits', 'James Clear', 'Self-Improvement', 'English', 320, 110000, 5000, 3],
            ['The Psychology of Money', 'Morgan Housel', 'Non-Fiction', 'English', 256, 105000, 5000, 2],
            ['Sapiens', 'Yuval Noah Harari', 'Non-Fiction', 'English', 443, 130000, 6000, 1],
            ['Rich Dad Poor Dad', 'Robert Kiyosaki', 'Non-Fiction', 'English', 336, 100000, 4500, 4],
            ['Mangga Kecil', 'Candra Rimbun', 'Children', 'Indonesian', 48, 50000, 2000, 6],
            ['Sang Pemimpi', 'Andrea Hirata', 'Children', 'Indonesian', 288, 85000, 4000, 2],
            ['One Piece Vol. 1', 'Eiichiro Oda', 'Comics', 'Japanese', 192, 60000, 3000, 5],
            ['Naruto Vol. 1', 'Masashi Kishimoto', 'Comics', 'Japanese', 192, 55000, 3000, 3],
            ['The Alchemist', 'Paulo Coelho', 'Religion & Spirituality', 'English', 208, 90000, 4000, 2],
        ];

        foreach ($books as [$title, $author, $genre, $language, $pages, $deposit, $daily, $stock]) {
            LibraryBook::firstOrCreate(
                ['title' => $title],
                [
                    'author' => $author,
                    'genre_id' => $genres[$genre]->id,
                    'language' => $language,
                    'pages' => $pages,
                    'deposit_price' => $deposit,
                    'daily_price' => $daily,
                    'stock' => $stock,
                    'is_active' => $title !== 'Naruto Vol. 1',
                ]
            );
        }
    }
}
