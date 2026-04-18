<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';
    
    protected $table = 'admins';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'role',
        'fcm_token',
        'last_notification_read_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_notification_read_at' => 'datetime',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the admin's notifications.
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the admin's unread notifications.
     */
    public function unreadNotifications(): MorphMany
    {
        return $this->notifications()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the admin's read notifications.
     */
    public function readNotifications(): MorphMany
    {
        return $this->notifications()
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Mark all notifications as read.
     *
     * @return void
     */
    public function markNotificationsAsRead()
    {
        $this->unreadNotifications->markAsRead();
        $this->last_notification_read_at = now();
        $this->save();
    }

    /**
     * Get the number of unread notifications.
     *
     * @return int
     */
    public function unreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string|null
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->fcm_token;
    }
}
