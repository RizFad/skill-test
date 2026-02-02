<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'is_draft',
        'published_at',
        'user_id',
    ];

    protected $casts = [
        'is_draft' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Query scope for active (published) posts
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('is_draft', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Laravel 12 attribute accessor
     */
    public function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => ! $this->is_draft &&
                $this->published_at !== null &&
                $this->published_at->isPast()
        );
    }
}
