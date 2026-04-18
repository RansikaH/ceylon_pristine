<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'session_id',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that performed the activity.
     */
    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    /**
     * Get the subject model of the activity.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo('subject');
    }

    /**
     * Scope a query to only include activities for a given action.
     */
    public function scopeForAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include activities for a given user.
     */
    public function scopeForUser($query, $user)
    {
        if ($user instanceof Model) {
            return $query->where('user_id', $user->getKey())
                        ->where('user_type', get_class($user));
        }

        return $query->where('user_id', $user);
    }

    /**
     * Scope a query to only include activities for a given subject.
     */
    public function scopeForSubject($query, Model $subject)
    {
        return $query->where('subject_type', get_class($subject))
                    ->where('subject_id', $subject->getKey());
    }

    /**
     * Scope a query to only include activities within a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate = null)
    {
        if ($endDate) {
            return $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->where('created_at', '>=', $startDate);
    }

    /**
     * Scope a query to only include recent activities.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get the formatted properties as a string.
     */
    public function getPropertiesSummaryAttribute(): string
    {
        if (empty($this->properties)) {
            return '';
        }

        $summary = [];
        foreach ($this->properties as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $summary[] = "{$key}: {$value}";
        }

        return implode(', ', $summary);
    }

    /**
     * Get the badge color for the action.
     */
    public function getActionBadgeColorAttribute(): string
    {
        $colors = [
            'login' => 'success',
            'logout' => 'secondary',
            'created' => 'primary',
            'updated' => 'info',
            'deleted' => 'danger',
            'failed_login' => 'warning',
            'password_changed' => 'warning',
            'password_reset' => 'warning',
            'profile_updated' => 'info',
            'registered' => 'success',
            'order_status_changed' => 'info',
            'order_bulk_status_changed' => 'info',
        ];
        
        return $colors[$this->action] ?? 'secondary';
    }
}
