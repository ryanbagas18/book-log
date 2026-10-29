<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'The Da Vinci Code',
            'author' => 'Dan Brown',
            'genre' => 'Thriller',
            'language' => 'English',
            'pages' => 489,
            'status' => 'finished',
            'rating' => 4,
            'started_at' => '2024-01-15',
            'finished_at' => '2024-02-10',
            'ownership' => 'owned',
            'purchase_place' => 'Gramedia',
            'condition' => 'new',
            'purchase_date' => '2024-01-10',
            'price' => 150000,
            'isbn' => '9780307474278',
            'cover_url' => 'https://books.google.com/books/content?id=P8V6AwAAQBAJ&pg=PP1&img=1',
            'notes' => 'Great thriller with historical elements.'
        ]);

        Book::create([
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'genre' => 'Historical Fiction',
            'language' => 'Indonesian',
            'pages' => 535,
            'status' => 'finished',
            'rating' => 5,
            'started_at' => '2024-02-15',
            'finished_at' => '2024-03-20',
            'ownership' => 'owned',
            'purchase_place' => 'Toko Buku Bekas',
            'condition' => 'preloved',
            'purchase_date' => '2024-02-10',
            'price' => 75000,
            'isbn' => '9789799748769',
            'cover_url' => '',
            'notes' => 'Indonesian literary masterpiece.'
        ]);

        Book::create([
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'genre' => 'Self-Help',
            'language' => 'English',
            'pages' => 320,
            'status' => 'reading',
            'rating' => null,
            'started_at' => '2024-04-01',
            'finished_at' => null,
            'ownership' => 'owned',
            'purchase_place' => 'Kinokuniya',
            'condition' => 'new',
            'purchase_date' => '2024-03-25',
            'price' => 280000,
            'isbn' => '9780735211292',
            'cover_url' => 'https://books.google.com/books/content?id=2WgZDwAAQBAJ&pg=PP1&img=1',
            'notes' => 'Currently reading chapter by chapter.'
        ]);

        Book::create([
            'title' => 'Sapiens: A Brief History of Humankind',
            'author' => 'Yuval Noah Harari',
            'genre' => 'History',
            'language' => 'English',
            'pages' => 443,
            'status' => 'to_read',
            'rating' => null,
            'started_at' => null,
            'finished_at' => null,
            'ownership' => 'owned',
            'purchase_place' => 'Periplus',
            'condition' => 'new',
            'purchase_date' => '2024-04-15',
            'price' => 320000,
            'isbn' => '9780062316097',
            'cover_url' => 'https://books.google.com/books/content?id=H13YBgAAQBAJ&pg=PP1&img=1',
            'notes' => 'Waiting to start after finishing Atomic Habits.'
        ]);

        Book::create([
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'genre' => 'Fiction',
            'language' => 'Indonesian',
            'pages' => 529,
            'status' => 'finished',
            'rating' => 5,
            'started_at' => '2023-12-01',
            'finished_at' => '2023-12-25',
            'ownership' => 'owned',
            'purchase_place' => 'Gramedia',
            'condition' => 'new',
            'purchase_date' => '2023-11-28',
            'price' => 120000,
            'isbn' => '9789799053610',
            'cover_url' => '',
            'notes' => 'Inspiring story about education in Belitong.'
        ]);

        Book::create([
            'title' => 'The Midnight Library',
            'author' => 'Matt Haig',
            'genre' => 'Fiction',
            'language' => 'English',
            'pages' => 304,
            'status' => 'dropped',
            'rating' => 2,
            'started_at' => '2024-03-01',
            'finished_at' => null,
            'ownership' => 'borrowed',
            'purchase_place' => null,
            'condition' => null,
            'purchase_date' => null,
            'price' => 0,
            'isbn' => '9780525559474',
            'cover_url' => 'https://books.google.com/books/content?id=vQ2IDwAAQBAJ&pg=PP1&img=1',
            'notes' => 'Borrowed from friend, could not finish.'
        ]);

        Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'genre' => 'Technology',
            'language' => 'English',
            'pages' => 464,
            'status' => 'reading',
            'rating' => null,
            'started_at' => '2024-04-20',
            'finished_at' => null,
            'ownership' => 'owned',
            'purchase_place' => 'Amazon',
            'condition' => 'new',
            'purchase_date' => '2024-04-18',
            'price' => 450000,
            'isbn' => '9780132350884',
            'cover_url' => 'https://books.google.com/books/content?id=pH_FAAAAMAAJ&pg=PP1&img=1',
            'notes' => 'Reference book for software development.'
        ]);

        Book::create([
            'title' => 'Dune',
            'author' => 'Frank Herbert',
            'genre' => 'Science Fiction',
            'language' => 'English',
            'pages' => 688,
            'status' => 'to_read',
            'rating' => null,
            'started_at' => null,
            'finished_at' => null,
            'ownership' => 'wishlist',
            'purchase_place' => null,
            'condition' => null,
            'purchase_date' => null,
            'price' => 0,
            'isbn' => '9780441172719',
            'cover_url' => 'https://books.google.com/books/content?id=E20hAAAAQBAJ&pg=PP1&img=1',
            'notes' => 'Planning to buy and read before watching the movie.'
        ]);

        Book::create([
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'genre' => 'Philosophy',
            'language' => 'Indonesian',
            'pages' => 272,
            'status' => 'finished',
            'rating' => 4,
            'started_at' => '2024-01-01',
            'finished_at' => '2024-01-20',
            'ownership' => 'owned',
            'purchase_place' => 'Gramedia',
            'condition' => 'new',
            'purchase_date' => '2023-12-30',
            'price' => 95000,
            'isbn' => '9786020486498',
            'cover_url' => '',
            'notes' => 'Stoicism philosophy in Indonesian context.'
        ]);

        Book::create([
            'title' => '1984',
            'author' => 'George Orwell',
            'genre' => 'Dystopian',
            'language' => 'English',
            'pages' => 328,
            'status' => 'finished',
            'rating' => 5,
            'started_at' => '2023-10-01',
            'finished_at' => '2023-10-25',
            'ownership' => 'owned',
            'purchase_place' => 'Toko Buku Bekas',
            'condition' => 'preloved',
            'purchase_date' => '2023-09-28',
            'price' => 60000,
            'isbn' => '9780451524935',
            'cover_url' => 'https://books.google.com/books/content?id=xtwzAAAAMAAJ&pg=PP1&img=1',
            'notes' => 'Classic dystopian novel, very relevant today.'
        ]);

        Book::create([
            'title' => 'Gone Girl',
            'author' => 'Gillian Flynn',
            'genre' => 'Thriller',
            'language' => 'English',
            'pages' => 432,
            'status' => 'to_read',
            'rating' => null,
            'started_at' => null,
            'finished_at' => null,
            'ownership' => 'skimmed',
            'purchase_place' => 'Library',
            'condition' => null,
            'purchase_date' => null,
            'price' => 0,
            'isbn' => '9780307588371',
            'cover_url' => 'https://books.google.com/books/content?id=zD7xAAAAQBAJ&pg=PP1&img=1',
            'notes' => 'Skimmed at library, might buy later.'
        ]);

        Book::create([
            'title' => 'Thinking, Fast and Slow',
            'author' => 'Daniel Kahneman',
            'genre' => 'Psychology',
            'language' => 'English',
            'pages' => 499,
            'status' => 'reading',
            'rating' => null,
            'started_at' => '2024-03-15',
            'finished_at' => null,
            'ownership' => 'owned',
            'purchase_place' => 'Kinokuniya',
            'condition' => 'new',
            'purchase_date' => '2024-03-10',
            'price' => 350000,
            'isbn' => '9780374533557',
            'cover_url' => 'https://books.google.com/books/content?id=O3JPAgAAQBAJ&pg=PP1&img=1',
            'notes' => 'Heavy reading, taking time to understand concepts.'
        ]);

        Book::create([
            'title' => 'Pulang',
            'author' => 'Leila S. Chudori',
            'genre' => 'Historical Fiction',
            'language' => 'Indonesian',
            'pages' => 416,
            'status' => 'finished',
            'rating' => 4,
            'started_at' => '2024-02-01',
            'finished_at' => '2024-02-28',
            'ownership' => 'owned',
            'purchase_place' => 'Gramedia',
            'condition' => 'new',
            'purchase_date' => '2024-01-28',
            'price' => 135000,
            'isbn' => '9786020323616',
            'cover_url' => '',
            'notes' => 'Historical fiction about 1965 events.'
        ]);

        Book::create([
            'title' => 'The Psychology of Money',
            'author' => 'Morgan Housel',
            'genre' => 'Finance',
            'language' => 'English',
            'pages' => 256,
            'status' => 'finished',
            'rating' => 5,
            'started_at' => '2024-01-05',
            'finished_at' => '2024-01-18',
            'ownership' => 'owned',
            'purchase_place' => 'Shopee',
            'condition' => 'new',
            'purchase_date' => '2024-01-02',
            'price' => 180000,
            'isbn' => '9780857197689',
            'cover_url' => 'https://books.google.com/books/content?id=tKm0DwAAQBAJ&pg=PP1&img=1',
            'notes' => 'Excellent insights on personal finance behavior.'
        ]);

        Book::create([
            'title' => 'Negeri 5 Menara',
            'author' => 'A. Fuadi',
            'genre' => 'Fiction',
            'language' => 'Indonesian',
            'pages' => 432,
            'status' => 'finished',
            'rating' => 4,
            'started_at' => '2023-11-01',
            'finished_at' => '2023-11-30',
            'ownership' => 'owned',
            'purchase_place' => 'Gramedia',
            'condition' => 'new',
            'purchase_date' => '2023-10-28',
            'price' => 110000,
            'isbn' => '9789792254842',
            'cover_url' => '',
            'notes' => 'Inspirational story about Islamic boarding school.'
        ]);
    }
}
