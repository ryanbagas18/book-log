<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BookSeeder::class,
            LibraryCatalogSeeder::class,
            RentalSeeder::class,
        ]);

        // Pre-v2 books belong to the owner's personal log.
        $adminId = User::where('email', 'admin@ryanlibrary.test')->value('id');
        Book::whereNull('user_id')->update(['user_id' => $adminId]);
    }
}
