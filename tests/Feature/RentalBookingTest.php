<?php

namespace Tests\Feature;

use App\Models\LibraryBook;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RentalBookingTest extends TestCase
{
    use RefreshDatabase;

    private function completeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'phone' => '+628123456789',
            'address' => 'Jl. Test No. 1, Jakarta',
        ], $attrs));
    }

    private function bookPayload(LibraryBook $book, array $overrides = []): array
    {
        return array_merge([
            'fulfillment_method' => 'delivery',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
            'terms_accepted' => '1',
            'proof_image' => UploadedFile::fake()->image('proof.jpg'),
        ], $overrides);
    }

    public function test_customer_can_book_and_prices_are_snapshotted(): void
    {
        Storage::fake('local');
        $user = $this->completeUser();
        $book = LibraryBook::factory()->create([
            'daily_price' => 5000,
            'deposit_price' => 100000,
            'stock' => 2,
        ]);

        $response = $this->actingAs($user)->post("/library/{$book->id}/rent", $this->bookPayload($book));

        $response->assertRedirect();

        $rental = Rental::firstOrFail();
        $this->assertSame($user->id, $rental->user_id);
        $this->assertSame('pending_verification', $rental->status);
        $this->assertSame(3, $rental->days);
        $this->assertSame(5000, $rental->daily_price);
        $this->assertSame(100000, $rental->deposit);
        $this->assertSame(15000, $rental->rental_fee);
        $this->assertSame(115000, $rental->total_amount);
        $this->assertSame($user->name, $rental->recipient_name);
        $this->assertSame($user->phone, $rental->recipient_phone);
        $this->assertSame($user->address, $rental->recipient_address);
        $this->assertNotNull($rental->terms_accepted_at);

        $payment = $rental->payment;
        $this->assertSame(115000, $payment->amount);
        $this->assertSame('pending', $payment->status);
        $this->assertStringStartsWith('payment-proofs/', $payment->proof_path);
        Storage::disk('local')->assertExists($payment->proof_path);
    }

    public function test_pickup_omits_recipient_address(): void
    {
        Storage::fake('local');
        $user = $this->completeUser();
        $book = LibraryBook::factory()->create(['stock' => 1]);

        $this->actingAs($user)->post("/library/{$book->id}/rent",
            $this->bookPayload($book, ['fulfillment_method' => 'pickup']));

        $this->assertNull(Rental::firstOrFail()->recipient_address);
    }

    public function test_stock_zero_or_inactive_book_cannot_be_rented(): void
    {
        Storage::fake('local');
        $user = $this->completeUser();
        $empty = LibraryBook::factory()->create(['stock' => 0]);
        $inactive = LibraryBook::factory()->create(['stock' => 3, 'is_active' => false]);

        $this->actingAs($user)->get("/library/{$empty->id}/rent")->assertNotFound();
        $this->actingAs($user)->get("/library/{$inactive->id}/rent")->assertNotFound();

        // stock drops to 0 between page load and submit: still rejected
        $book = LibraryBook::factory()->create(['stock' => 1]);
        $book->update(['stock' => 0]);
        $this->actingAs($user)->post("/library/{$book->id}/rent", $this->bookPayload($book))
            ->assertSessionHasErrors();
        $this->assertDatabaseCount('rentals', 0);
    }

    public function test_tampered_client_price_is_ignored(): void
    {
        Storage::fake('local');
        $user = $this->completeUser();
        $book = LibraryBook::factory()->create(['daily_price' => 5000, 'deposit_price' => 100000]);

        $this->actingAs($user)->post("/library/{$book->id}/rent",
            $this->bookPayload($book, ['daily_price' => 1, 'total_amount' => 1, 'rental_fee' => 1]));

        $rental = Rental::firstOrFail();
        $this->assertSame(5000, $rental->daily_price);
        $this->assertSame(15000, $rental->rental_fee);
        $this->assertSame(115000, $rental->total_amount);
    }

    public function test_terms_must_be_accepted(): void
    {
        Storage::fake('local');
        $user = $this->completeUser();
        $book = LibraryBook::factory()->create();

        $payload = $this->bookPayload($book);
        unset($payload['terms_accepted']);

        $this->actingAs($user)->post("/library/{$book->id}/rent", $payload)
            ->assertSessionHasErrors('terms_accepted');
        $this->assertDatabaseCount('rentals', 0);
    }

    public function test_dates_and_max_days_validation(): void
    {
        Storage::fake('local');
        $user = $this->completeUser();
        $book = LibraryBook::factory()->create();

        $this->actingAs($user)->post("/library/{$book->id}/rent",
            $this->bookPayload($book, ['start_date' => now()->subDay()->toDateString()]))
            ->assertSessionHasErrors('start_date');

        $this->actingAs($user)->post("/library/{$book->id}/rent",
            $this->bookPayload($book, [
                'start_date' => now()->toDateString(),
                'end_date' => now()->addDays(31)->toDateString(),
            ]))->assertSessionHasErrors('end_date');
    }

    public function test_incomplete_profile_is_redirected_from_rent_routes(): void
    {
        $user = User::factory()->create(['phone' => null, 'address' => null]);
        $book = LibraryBook::factory()->create(['stock' => 1]);

        $this->actingAs($user)->get("/library/{$book->id}/rent")
            ->assertRedirect(route('profile.edit'));
    }

    public function test_cancel_only_for_owner_and_only_while_pending(): void
    {
        $owner = $this->completeUser();
        $other = $this->completeUser();
        $rental = Rental::factory()->create(['user_id' => $owner->id, 'status' => 'pending_verification']);

        // other user gets 404
        $this->actingAs($other)->post("/rentals/{$rental->id}/cancel")->assertNotFound();
        $this->assertSame('pending_verification', $rental->fresh()->status);

        // owner cancels
        $this->actingAs($owner)->post("/rentals/{$rental->id}/cancel")
            ->assertRedirect(route('rentals.index'));
        $this->assertSame('cancelled', $rental->fresh()->status);

        // cannot cancel again
        $this->actingAs($owner)->post("/rentals/{$rental->id}/cancel")
            ->assertSessionHas('error');
        $this->assertSame('cancelled', $rental->fresh()->status);
    }

    public function test_proof_is_private_and_owner_or_admin_only(): void
    {
        Storage::fake('local');
        $owner = $this->completeUser();
        $other = $this->completeUser();
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        $rental = Rental::factory()->create(['user_id' => $owner->id]);
        $path = 'payment-proofs/test-proof.jpg';
        Storage::disk('local')->put($path, 'fake-image-data');
        $rental->payment()->create([
            'amount' => $rental->total_amount,
            'proof_path' => $path,
            'status' => 'pending',
        ]);

        // stored on private disk, not public
        Storage::disk('public')->assertMissing($path);

        // other customer forbidden
        $this->actingAs($other)->get("/rentals/{$rental->id}/proof")->assertForbidden();
        // owner and admin allowed
        $this->actingAs($owner)->get("/rentals/{$rental->id}/proof")->assertOk();
        $this->actingAs($admin)->get("/rentals/{$rental->id}/proof")->assertOk();
    }

    public function test_guest_cannot_access_rental_routes(): void
    {
        $rental = Rental::factory()->create();
        $book = $rental->libraryBook;

        $this->get('/rentals')->assertRedirect(route('login'));
        $this->get("/rentals/{$rental->id}")->assertRedirect(route('login'));
        $this->get("/library/{$book->id}/rent")->assertRedirect(route('login'));
    }
}
