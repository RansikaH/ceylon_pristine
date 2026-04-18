<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    /**
     * Log user activity.
     */
    public static function log(string $action, string $description = null, Model $subject = null, array $properties = []): ActivityLog
    {
        try {
            // Get user from admin guard first, then regular guard
            $user = auth()->guard('admin')->user() ?? auth()->user();
            $request = request();

            $data = [
                'action' => $action,
                'subject_type' => $subject?->getMorphClass(),
                'subject_id' => $subject?->getKey(),
                'description' => $description,
                'properties' => $properties,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent(),
            ];

            // Add session ID only if session is available
            if ($request && $request->hasSession()) {
                $data['session_id'] = $request->session()->getId();
            } else {
                $data['session_id'] = null;
            }

            // Add user info if authenticated, otherwise keep as null for system logs
            if ($user) {
                $data['user_id'] = $user->id;
                $data['user_type'] = get_class($user);
            } else {
                $data['user_id'] = null;
                $data['user_type'] = null;
            }

            $activityLog = ActivityLog::create($data);

            return $activityLog;
        } catch (\Exception $e) {
            // Log the error but don't break the application
            \Log::error('ActivityLogger failed: ' . $e->getMessage(), [
                'action' => $action,
                'description' => $description,
                'subject' => $subject?->getMorphClass() . ':' . $subject?->getKey(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return a dummy activity log to avoid breaking the application
            return new ActivityLog();
        }
    }

    /**
     * Log login activity.
     */
    public static function login(): ActivityLog
    {
        return self::log('login', 'User logged in');
    }

    /**
     * Log logout activity.
     */
    public static function logout(): ActivityLog
    {
        return self::log('logout', 'User logged out');
    }

    /**
     * Log failed login attempt.
     */
    public static function failedLogin(string $email): ActivityLog
    {
        return self::log('failed_login', "Failed login attempt for: {$email}", null, ['email' => $email]);
    }

    /**
     * Log password change.
     */
    public static function passwordChanged(): ActivityLog
    {
        return self::log('password_changed', 'User changed their password');
    }

    /**
     * Log profile update.
     */
    public static function profileUpdated(array $changes = []): ActivityLog
    {
        return self::log('profile_updated', 'User updated their profile', null, ['changes' => $changes]);
    }

    /**
     * Log model creation.
     */
    public static function created(Model $model, string $description = null): ActivityLog
    {
        $description = $description ?? "Created new " . class_basename($model);
        return self::log('created', $description, $model, ['attributes' => $model->toArray()]);
    }

    /**
     * Log model update.
     */
    public static function updated(Model $model, array $changes = [], string $description = null): ActivityLog
    {
        $description = $description ?? "Updated " . class_basename($model);
        return self::log('updated', $description, $model, ['changes' => $changes]);
    }

    /**
     * Log model deletion.
     */
    public static function deleted(Model $model, string $description = null): ActivityLog
    {
        $description = $description ?? "Deleted " . class_basename($model);
        return self::log('deleted', $description, $model, ['attributes' => $model->toArray()]);
    }

    /**
     * Log product activities.
     */
    public static function productCreated($product): ActivityLog
    {
        return self::created($product, "Created new product: {$product->name}");
    }

    public static function productUpdated($product, array $changes): ActivityLog
    {
        return self::updated($product, $changes, "Updated product: {$product->name}");
    }

    public static function productDeleted($product): ActivityLog
    {
        return self::deleted($product, "Deleted product: {$product->name}");
    }

    /**
     * Log order activities.
     */
    public static function orderCreated($order): ActivityLog
    {
        return self::created($order, "Created new order #{$order->id}");
    }

    public static function orderUpdated($order, array $changes): ActivityLog
    {
        return self::updated($order, $changes, "Updated order #{$order->id}");
    }

    public static function orderStatusChanged($order, string $oldStatus, string $newStatus): ActivityLog
    {
        return self::log('order_status_changed', 
            "Order #{$order->id} status changed from {$oldStatus} to {$newStatus}", 
            $order, 
            ['old_status' => $oldStatus, 'new_status' => $newStatus]
        );
    }

    /**
     * Log category activities.
     */
    public static function categoryCreated($category): ActivityLog
    {
        return self::created($category, "Created new category: {$category->name}");
    }

    public static function categoryUpdated($category, array $changes): ActivityLog
    {
        return self::updated($category, $changes, "Updated category: {$category->name}");
    }

    public static function categoryDeleted($category): ActivityLog
    {
        return self::deleted($category, "Deleted category: {$category->name}");
    }

    /**
     * Log user management activities (for admins).
     */
    public static function userCreated($user): ActivityLog
    {
        return self::created($user, "Created new user: {$user->name}");
    }

    public static function userUpdated($user, array $changes): ActivityLog
    {
        return self::updated($user, $changes, "Updated user: {$user->name}");
    }

    public static function userDeleted($user): ActivityLog
    {
        return self::deleted($user, "Deleted user: {$user->name}");
    }

    /**
     * Log custom activity.
     */
    public static function custom(string $action, string $description, Model $subject = null, array $properties = []): ActivityLog
    {
        return self::log($action, $description, $subject, $properties);
    }
}
