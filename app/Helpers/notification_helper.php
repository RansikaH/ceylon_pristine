<?php

use App\Models\Admin;
use App\Notifications\AdminNotification;

if (! function_exists('notify_admins')) {
    /**
     * Send a notification to all admin users.
     *
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param string $type
     * @return void
     */
    function notify_admins($title, $message, $url = null, $type = 'info')
    {
        $admins = Admin::all();
        
        foreach ($admins as $admin) {
            $admin->notify(new AdminNotification($title, $message, $url, $type));
        }
    }
}

if (! function_exists('notify_admin')) {
    /**
     * Send a notification to a specific admin user.
     *
     * @param \App\Models\Admin $admin
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param string $type
     * @return void
     */
    function notify_admin($admin, $title, $message, $url = null, $type = 'info')
    {
        $admin->notify(new AdminNotification($title, $message, $url, $type));
    }
}

if (! function_exists('notify_user')) {
    /**
     * Send a notification to a specific user.
     *
     * @param \App\Models\User $user
     * @param string $title
     * @param string $message
     * @param string|null $url
     * @param string $type
     * @return void
     */
    function notify_user($user, $title, $message, $url = null, $type = 'info')
    {
        $user->notify(new AdminNotification($title, $message, $url, $type));
    }
}
