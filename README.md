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

## Ryan's Library (rental platform)

The app is a hybrid: a per-user personal book tracker plus a commercial book rental service ("Ryan's Library").

- **Public catalog** at `/library` with search and genre filters; `/terms` holds the rental terms
- **Customer flow**: browse catalog -> book a rental (delivery or pickup) -> upload a bank-transfer proof -> owner verifies -> hand-over -> return -> deposit refund
- **Admin area** at `/admin` (admin role only): dashboard stats, rentals verification/circulation, genres, library book catalog
- Payment is a **manual bank transfer**; proofs are stored on the private disk (`storage/app/private/payment-proofs`) and served only through an authorized route
- Money is stored as integer Rupiah; `formatRupiah()` renders "Rp 12.500"
- Rental prices and deposit are snapshotted on the booking; stock decrements on approval and increments on return

### Environment variables (rental)

```
RENTAL_BANK_NAME=           # bank name shown on the booking page
RENTAL_BANK_ACCOUNT_NUMBER= # account number customers transfer to
RENTAL_BANK_ACCOUNT_HOLDER= # account holder name
RENTAL_MAX_DAYS=30          # maximum rental length in days
```

Without `RENTAL_BANK_*` values the booking page warns that bank details are not configured.

### Accounts and roles

- `admin` — the owner; sees `/admin` and all customer data needed for verification
- `customer` — personal book log + rentals; must complete their profile (name, email, phone, address) before renting

`role` is never mass-assignable; registration always creates `customer`.

Seeded dev accounts (password `password` for all, dev only):

- `admin@ryanlibrary.test` — admin
- `customer.complete@example.test` — customer with complete profile
- `customer.incomplete@example.test` — customer with incomplete profile

### Password reset

Forgot-password is intentionally not implemented (needs SMTP). Reset via tinker:

```bash
php artisan tinker
>>> $u = App\Models\User::where('email', 'user@example.com')->first();
>>> $u->password = 'new-password'; $u->save();
```

### Backup

Back up the database regularly, and also `storage/app/private` — it holds payment proofs and other sensitive uploads that are not in the database.

## Database Schema

- `books` — personal reading/purchase log per user (soft deletes)
- `genres`, `library_books` — rental catalog (library books soft-delete only)
- `rentals`, `payments` — bookings and transfer proofs
- `users` — role, phone, address in addition to Laravel defaults
