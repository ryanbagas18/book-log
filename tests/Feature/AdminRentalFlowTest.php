<?php

namespace Tests\Feature;

use App\Models\LibraryBook;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRentalFlowTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $admin = User::factory()->create();
        $admin->role = 'admin';
        $admin->save();

        return $admin;
    }

    private function rental(array $attrs = []): Rental
    {
        return Rental::factory()->create(array_merge([
            'status' => 'pending_verification',
        ], $attrs));
    }

    private function withPayment(Rental $rental): void
    {
        $rental->payment()->create([
            'amount' => $rental->total_amount,
            'proof_path' => 'payment-proofs/x.jpg',
            'status' => 'pending',
        ]);
    }

    public function test_full_circulation_flow(): void
    {
        $admin = $this->admin();
        $book = LibraryBook::factory()->create(['stock' => 2]);
        $rental = $this->rental(['library_book_id' => $book->id]);
        $this->withPayment($rental);

        // approve: stock -1, payment verified
        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/approve")
            ->assertRedirect(route('admin.rentals.show', $rental));
        $this->assertSame('approved', $rental->fresh()->status);
        $this->assertSame(1, $book->fresh()->stock);
        $this->assertSame('verified', $rental->payment->fresh()->status);
        $this->assertSame($admin->id, $rental->payment->fresh()->verified_by);

        // hand over
        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/hand-over");
        $rental->refresh();
        $this->assertSame('on_rent', $rental->status);
        $this->assertNotNull($rental->handed_over_at);

        // return with partial deduction
        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/return", [
            'deposit_deduction' => 20000,
        ]);
        $rental->refresh();
        $this->assertSame('returned', $rental->status);
        $this->assertSame(2, $book->fresh()->stock);
        $this->assertSame(20000, $rental->deposit_deduction);
        $this->assertSame('pending', $rental->deposit_refund_status);

        // refund deposit
        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/refund-deposit");
        $rental->refresh();
        $this->assertSame('refunded', $rental->deposit_refund_status);
        $this->assertNotNull($rental->deposit_refunded_at);
    }

    public function test_double_approve_is_blocked(): void
    {
        $admin = $this->admin();
        $rental = $this->rental(['status' => 'approved', 'approved_at' => now()]);

        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/approve")
            ->assertSessionHas('error');
        $this->assertSame('approved', $rental->fresh()->status);
    }

    public function test_approve_fails_at_stock_zero(): void
    {
        $admin = $this->admin();
        $book = LibraryBook::factory()->create(['stock' => 0]);
        $rental = $this->rental(['library_book_id' => $book->id]);
        $this->withPayment($rental);

        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/approve")
            ->assertSessionHas('error');
        $this->assertSame('pending_verification', $rental->fresh()->status);
        $this->assertSame('pending', $rental->payment->fresh()->status);
    }

    public function test_reject_requires_reason_and_leaves_stock_untouched(): void
    {
        $admin = $this->admin();
        $book = LibraryBook::factory()->create(['stock' => 1]);
        $rental = $this->rental(['library_book_id' => $book->id]);
        $this->withPayment($rental);

        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/reject", [])
            ->assertSessionHasErrors('rejection_reason');

        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/reject", [
            'rejection_reason' => 'Proof image unreadable',
        ]);

        $rental->refresh();
        $this->assertSame('rejected', $rental->status);
        $this->assertSame('Proof image unreadable', $rental->rejection_reason);
        $this->assertSame('rejected', $rental->payment->fresh()->status);
        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_invalid_transitions_are_refused(): void
    {
        $admin = $this->admin();

        // cannot hand over a pending rental
        $pending = $this->rental();
        $this->actingAs($admin)->post("/admin/rentals/{$pending->id}/hand-over")
            ->assertSessionHas('error');
        $this->assertSame('pending_verification', $pending->fresh()->status);

        // cannot return a non-on_rent rental
        $this->actingAs($admin)->post("/admin/rentals/{$pending->id}/return", [
            'deposit_deduction' => 0,
        ])->assertSessionHas('error');

        // cannot refund a non-pending refund
        $returned = $this->rental(['status' => 'returned', 'returned_at' => now()]);
        $this->actingAs($admin)->post("/admin/rentals/{$returned->id}/refund-deposit")
            ->assertSessionHas('error');
        $this->assertSame('none', $returned->fresh()->deposit_refund_status);
    }

    public function test_deduction_above_deposit_is_refused(): void
    {
        $admin = $this->admin();
        $book = LibraryBook::factory()->create(['stock' => 1]);
        $rental = $this->rental([
            'library_book_id' => $book->id,
            'status' => 'on_rent',
            'handed_over_at' => now(),
            'deposit' => 50000,
        ]);

        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/return", [
            'deposit_deduction' => 60000,
        ])->assertSessionHasErrors('deposit_deduction');

        $this->assertSame('on_rent', $rental->fresh()->status);
        $this->assertSame(1, $book->fresh()->stock);
    }

    public function test_full_deduction_forfeits_refund(): void
    {
        $admin = $this->admin();
        $book = LibraryBook::factory()->create(['stock' => 1]);
        $rental = $this->rental([
            'library_book_id' => $book->id,
            'status' => 'on_rent',
            'handed_over_at' => now(),
            'deposit' => 50000,
        ]);

        $this->actingAs($admin)->post("/admin/rentals/{$rental->id}/return", [
            'deposit_deduction' => 50000,
        ]);

        $this->assertSame('forfeited', $rental->fresh()->deposit_refund_status);
    }

    public function test_overdue_list_shows_only_late_on_rent_rentals(): void
    {
        $admin = $this->admin();

        $overdue = $this->rental([
            'status' => 'on_rent',
            'handed_over_at' => now()->subDays(10),
            'end_date' => now()->subDays(3)->toDateString(),
        ]);
        $this->rental(['status' => 'on_rent', 'end_date' => now()->addDays(3)->toDateString()]);
        $this->rental(['status' => 'returned', 'end_date' => now()->subDays(5)->toDateString()]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertOk()->assertSee($overdue->libraryBook->title);
        $this->assertTrue($overdue->fresh()->isOverdue());
        $this->assertSame(3, $overdue->fresh()->daysLate());
    }

    public function test_admin_rentals_index_filters_by_status(): void
    {
        $admin = $this->admin();
        $pending = $this->rental(['status' => 'pending_verification']);
        $this->rental(['status' => 'approved']);

        $this->actingAs($admin)->get('/admin/rentals?status=pending_verification')
            ->assertOk()
            ->assertSee($pending->libraryBook->title);
    }

    public function test_customer_cannot_use_admin_rental_routes(): void
    {
        $user = User::factory()->create();
        $rental = $this->rental();

        $this->actingAs($user)->get('/admin/rentals')->assertForbidden();
        $this->actingAs($user)->get("/admin/rentals/{$rental->id}")->assertForbidden();
        $this->actingAs($user)->post("/admin/rentals/{$rental->id}/approve")->assertForbidden();
    }
}
