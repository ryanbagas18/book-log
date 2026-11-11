<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rental extends Model
{
    /** @use HasFactory<\Database\Factories\RentalFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'library_book_id',
        'fulfillment_method',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'start_date',
        'end_date',
        'days',
        'daily_price',
        'deposit',
        'rental_fee',
        'total_amount',
        'status',
        'rejection_reason',
        'terms_accepted_at',
        'approved_at',
        'handed_over_at',
        'returned_at',
        'deposit_deduction',
        'deposit_refund_status',
        'deposit_refunded_at',
        'admin_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'daily_price' => 'integer',
        'deposit' => 'integer',
        'rental_fee' => 'integer',
        'total_amount' => 'integer',
        'deposit_deduction' => 'integer',
        'terms_accepted_at' => 'datetime',
        'approved_at' => 'datetime',
        'handed_over_at' => 'datetime',
        'returned_at' => 'datetime',
        'deposit_refunded_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function libraryBook(): BelongsTo
    {
        return $this->belongsTo(LibraryBook::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_verification' => 'Pending Verification',
            'approved' => 'Approved',
            'on_rent' => 'On Rent',
            'returned' => 'Returned',
            'rejected' => 'Rejected',
            'cancelled' => 'Cancelled',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'pending_verification' => 'bg-warning text-dark',
            'approved' => 'bg-info text-dark',
            'on_rent' => 'bg-primary',
            'returned' => 'bg-success',
            'rejected', 'cancelled' => 'bg-secondary',
        };
    }

    public function isOverdue(): bool
    {
        return $this->status === 'on_rent' && $this->end_date->isPast();
    }

    public function daysLate(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        return (int) $this->end_date->diffInDays(now());
    }
}
