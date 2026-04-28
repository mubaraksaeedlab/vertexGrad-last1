<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'body',
        'audience',
        'is_pinned',
        'is_active',
        'publish_at',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned'  => 'boolean',
        'is_active'  => 'boolean',
        'publish_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished(Builder $query): Builder
    {
        $now = now();

        return $query
            ->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('publish_at')
                  ->orWhere('publish_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', $now);
            });
    }

    public function scopeForAudience(Builder $query, string $audience): Builder
    {
        return $query->where(function ($q) use ($audience) {
            $q->where('audience', 'all')
              ->orWhere('audience', $audience);
        });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderByDesc('is_pinned')
            ->orderByDesc('publish_at')
            ->orderByDesc('created_at');
    }
    public function getDisplayStatusAttribute(): string
{
    if (! $this->is_active) {
        return 'Disabled';
    }

    if ($this->publish_at && $this->publish_at->isFuture()) {
        return 'Scheduled';
    }

    if ($this->expires_at && $this->expires_at->isPast()) {
        return 'Expired';
    }

    return 'Active';
}
}