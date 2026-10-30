<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'author',
        'genre',
        'language',
        'pages',
        'isbn',
        'status',
        'rating',
        'started_at',
        'finished_at',
        'ownership',
        'purchase_place',
        'condition',
        'purchase_date',
        'price',
        'cover_image',
        'cover_url',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'date',
        'finished_at' => 'date',
        'purchase_date' => 'date',
        'price' => 'integer',
    ];

    public function getFormattedPriceAttribute(): string
    {
        if ($this->price === 0) {
            return 'Free';
        }

        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }


    public function getCoverImageUrlAttribute(): ?string
    {
        if ($this->cover_url) {
            return $this->cover_url;
        }

        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return null;
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'to_read' => 'To Read',
            'reading' => 'Reading',
            'finished' => 'Finished',
            'dropped' => 'Dropped',
        };
    }

    public function getOwnershipLabelAttribute(): string
    {
        return match($this->ownership) {
            'owned' => 'Owned',
            'skimmed' => 'Skimmed (in store)',
            'borrowed' => 'Borrowed',
            'wishlist' => 'Wishlist',
        };
    }

    /**
     * Scope a query to only include owned books.
     *
     * @param \Illuminate\Database\Eloquent\Builder<\App\Models\Book> $query
     * @return \Illuminate\Database\Eloquent\Builder<\App\Models\Book>
     */
  public function scopeOwned($query)
    {
        return $query->where('ownership', 'owned');
    }  
}
