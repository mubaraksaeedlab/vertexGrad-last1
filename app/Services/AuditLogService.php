<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditLogService
{
    public static function log(
        string $event,
        string $description,
        ?string $category = null,
        ?Model $subject = null,
        ?array $properties = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Request $request = null
    ): void {
        $user = auth('admin')->user() ?? auth('web')->user();

        AuditLog::create([
            'user_id'       => $user?->id,
            'user_type'     => $user?->role ?? ($user ? class_basename($user) : null),
            'user_name'     => $user?->name ?? $user?->username ?? 'System',
            'event'         => $event,
            'category'      => $category,
            'description'   => $description,
            'subject_type'  => $subject ? get_class($subject) : null,
            'subject_id'    => $subject?->getKey(),
            'subject_title' => self::resolveSubjectTitle($subject),
            'properties'    => $properties,
            'old_values'    => $oldValues,
            'new_values'    => $newValues,
            'ip_address'    => $request?->ip() ?? request()?->ip(),
            'user_agent'    => $request?->userAgent() ?? request()?->userAgent(),
            'created_at'    => now(),
        ]);
    }

    protected static function resolveSubjectTitle(?Model $subject): ?string
    {
        if (!$subject) {
            return null;
        }

        foreach (['title', 'name', 'subject', 'email'] as $field) {
            if (isset($subject->{$field}) && filled($subject->{$field})) {
                return (string) $subject->{$field};
            }
        }

        return class_basename($subject) . ' #' . $subject->getKey();
    }
}