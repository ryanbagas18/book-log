<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryBook extends Model
{
    /** @use HasFactory<\Database\Factories\LibraryBookFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'genre_id',
        'title',
        'author',
        'isbn',
        'synopsis',
        'goodreads_url',
        'language',
        'pages',
        'cover_image',
        'deposit_price',
        'daily_price',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'deposit_price' => 'integer',
        'daily_price' => 'integer',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }
}
