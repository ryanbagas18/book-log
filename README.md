# Book Log & Purchase Tracker

A clean Laravel 11 web application for tracking personal book collections and reading progress.

## Features

- Full CRUD operations for books
- Track reading status (To Read, Reading, Finished, Dropped)
- Track ownership (Owned, Skimmed, Borrowed, Wishlist)
- Purchase tracking with price in Indonesian Rupiah
- Cover image upload
- Rating system (1-5 stars)
- Search and filter by status, ownership, language
- Sort by latest, oldest, title, price, rating
- Statistics dashboard (monthly spending, total value, books finished this year)
- Responsive Bootstrap 5 UI
- Soft delete support

## Tech Stack

- Laravel 11
- PHP 8.2+
- Blade templates
- Bootstrap 5 (CDN)
- SQLite (default) or MySQL
- Vanilla JavaScript

## Setup Instructions

### Prerequisites

- PHP 8.2 or higher
- Composer
- SQLite or MySQL database
- SQLite PDO extension (if using SQLite)

### Installation

1. Navigate to the project directory:

    ```bash
    cd book-log
    ```

2. Install dependencies:

    ```bash
    composer install
    ```

3. Copy environment file:

    ```bash
    cp .env.example .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Configure database in `.env`:

    **For SQLite (default):**

    ```
    DB_CONNECTION=sqlite
    ```

    Then create the database file:

    ```bash
    touch database/database.sqlite
    ```

    Note: Ensure PHP has the SQLite PDO extension installed (`php-pdo-sqlite` on Linux).

    **For MySQL:**

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=book_log
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

    Create the database before running migrations:

    ```bash
    mysql -u root -p -e "CREATE DATABASE book_log;"
    ```

6. Run migrations and seed the database:

    ```bash
    php artisan migrate --seed
    ```

7. Create storage link for cover images:

    ```bash
    php artisan storage:link
    ```

8. Start the development server:

    ```bash
    php artisan serve
    ```

9. Open your browser and visit: `http://localhost:8000`

## Database Schema

The `books` table includes:

- Basic info: title, author, ISBN, genre, language, pages
- Reading tracking: status, rating, started_at, finished_at
- Ownership: ownership type, purchase place, condition, purchase date, price
- Cover image and notes
- Soft deletes enabled

## Notes

- The application is designed for personal, local use (no authentication)
- All prices are in Indonesian Rupiah (Rp)
- Currency format: "Rp 12.500" (dot as thousands separator, no decimals)
- Price of 0 displays as "Free"
- Cover images are stored in `storage/app/public/covers`
- With soft deletes, cover images are retained even after deletion
