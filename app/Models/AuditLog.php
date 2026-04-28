<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_type',
        'user_name',
        'event',
        'category',
        'description',
        'subject_type',
        'subject_id',
        'subject_title',
        'properties',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'properties'  => 'array',
        'old_values'  => 'array',
        'new_values'  => 'array',
        'created_at'  => 'datetime',
    ];

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->orderByDesc('created_at')->orderByDesc('id');
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhere('user_name', 'like', "%{$search}%")
              ->orWhere('user_type', 'like', "%{$search}%")
              ->orWhere('event', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%")
              ->orWhere('subject_title', 'like', "%{$search}%");
        });
    }

    public function scopeForEvent(Builder $query, ?string $event): Builder
    {
        if (!$event) {
            return $query;
        }

        return $query->where('event', $event);
    }

    public function scopeForCategory(Builder $query, ?string $category): Builder
    {
        if (!$category) {
            return $query;
        }

        return $query->where('category', $category);
    }

    public function scopeForUser(Builder $query, $userId): Builder
    {
        if (!$userId) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }

    public function scopeForSubject(Builder $query, ?string $subjectType, $subjectId): Builder
    {
        if (!$subjectType || !$subjectId) {
            return $query;
        }

        return $query->where('subject_type', $subjectType)
                     ->where('subject_id', $subjectId);
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created'  => 'Created',
            'updated'  => 'Updated',
            'deleted'  => 'Deleted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'restored' => 'Restored',
            'login'    => 'Login',
            'logout'   => 'Logout',
            'exported' => 'Exported',
            default    => ucfirst(str_replace('_', ' ', $this->event)),
        };
    }
}