# User Notification System Guide

## Overview
The user dashboard now includes a comprehensive notification system that displays messages to specific users with popup alerts and a dedicated notifications section.

## Features

### 1. **Automatic Popup Alerts**
- When users log in to their dashboard, any unread notifications will automatically appear as popup alerts
- Uses SweetAlert2 for beautiful, professional-looking alerts
- Notifications appear sequentially with a 500ms delay between each
- Users can mark notifications as read directly from the popup

### 2. **Dashboard Notifications Section**
- Displays all unread notifications in a dedicated section on the dashboard
- Shows notification title, message, and time
- Allows users to mark individual notifications as read
- Option to mark all notifications as read at once

### 3. **Alert Banner**
- A dismissible alert banner shows the count of unread messages
- Located at the top of the dashboard for immediate visibility

## How to Send Notifications to Users

### Method 1: Send to a Specific User (Programmatically)

```php
use App\Models\User;
use App\Notifications\UserNotification;

// Find the user
$user = User::find($userId);

// Send notification
$user->notify(new UserNotification(
    'Notification Title',           // Title
    'Your notification message',    // Message
    url('/dashboard'),              // URL (optional, defaults to /dashboard)
    'success'                       // Type: info, success, warning, error
));
```

### Method 2: Send to Multiple Users

```php
use App\Models\User;
use App\Notifications\UserNotification;

// Get users (e.g., all regular users)
$users = User::where('role', 'user')->get();

// Send to each user
foreach ($users as $user) {
    $user->notify(new UserNotification(
        'Important Announcement',
        'We have exciting new products available!',
        url('/shop'),
        'info'
    ));
}
```

### Method 3: Using Test Routes (Admin Only)

The system includes test routes for easy testing:

#### Send to a Specific User:
```
GET /test-user-notification/{userId}
```
Example: `http://yourdomain.com/test-user-notification/1`

#### Send to All Users:
```
GET /test-user-notification-all
```
Example: `http://yourdomain.com/test-user-notification-all`

**Note:** These routes require admin authentication.

## Notification Types

The notification system supports different types with corresponding icons and colors:

- **info** (blue) - General information
- **success** (green) - Success messages, confirmations
- **warning** (yellow) - Warnings, important notices
- **error** (red) - Error messages, critical alerts

## Common Use Cases

### 1. Order Status Updates
```php
$user->notify(new UserNotification(
    'Order Shipped',
    'Your order #' . $order->id . ' has been shipped and is on its way!',
    route('orders.show', $order),
    'success'
));
```

### 2. Promotional Messages
```php
$user->notify(new UserNotification(
    'Special Offer',
    'Get 20% off on all products this weekend!',
    route('shop.full'),
    'info'
));
```

### 3. Account Updates
```php
$user->notify(new UserNotification(
    'Profile Updated',
    'Your profile information has been successfully updated.',
    route('profile.edit'),
    'success'
));
```

### 4. Important Alerts
```php
$user->notify(new UserNotification(
    'Payment Failed',
    'Your recent payment could not be processed. Please update your payment method.',
    route('profile.edit'),
    'error'
));
```

## Integration Examples

### In a Controller (e.g., OrderController)
```php
use App\Notifications\UserNotification;

public function updateStatus(Order $order, Request $request)
{
    $order->update(['status' => $request->status]);
    
    // Notify the user
    $order->user->notify(new UserNotification(
        'Order Status Updated',
        'Your order #' . $order->id . ' status has been updated to: ' . $request->status,
        route('orders.show', $order),
        'info'
    ));
    
    return redirect()->back()->with('success', 'Order updated and user notified!');
}
```

### In an Event Listener
```php
use App\Notifications\UserNotification;

public function handle(OrderPlaced $event)
{
    $event->order->user->notify(new UserNotification(
        'Order Confirmed',
        'Thank you for your order! Order #' . $event->order->id . ' has been received.',
        route('orders.show', $event->order),
        'success'
    ));
}
```

## User Actions

Users can interact with notifications in several ways:

1. **View Popup**: Notifications automatically popup when visiting the dashboard
2. **Mark as Read from Popup**: Click "Mark as Read" button in the popup
3. **Mark as Read from List**: Click the checkmark button next to each notification
4. **Mark All as Read**: Click "Mark All as Read" button at the top of the notifications section
5. **Dismiss Alert Banner**: Click the X button on the alert banner

## Technical Details

### Database Structure
Notifications are stored in the `notifications` table with:
- `id` (UUID)
- `type` (notification class)
- `notifiable_type` and `notifiable_id` (user reference)
- `data` (JSON containing title, message, url, type)
- `read_at` (timestamp, null for unread)
- `created_at` and `updated_at`

### Routes
User notification routes (authenticated users):
- `GET /notifications` - View all notifications
- `GET /notifications/latest` - Get latest unread notifications (API)
- `POST /notifications/mark-all-read` - Mark all as read
- `POST /notifications/{id}/mark-as-read` - Mark specific notification as read
- `DELETE /notifications/{id}` - Delete a notification

### Files Modified/Created
1. **Created**: `app/Notifications/UserNotification.php`
2. **Modified**: `app/Http/Controllers/DashboardController.php`
3. **Modified**: `resources/views/dashboard.blade.php`
4. **Modified**: `routes/notifications.php`

## Best Practices

1. **Keep messages concise** - Users should understand the notification at a glance
2. **Use appropriate types** - Match the notification type to the message importance
3. **Include relevant URLs** - Link to pages where users can take action
4. **Don't spam users** - Only send important notifications
5. **Test before production** - Use the test routes to verify notifications work correctly

## Troubleshooting

### Notifications not appearing?
1. Check if the user has unread notifications in the database
2. Verify SweetAlert2 is loaded (check browser console)
3. Ensure the user is authenticated
4. Check browser console for JavaScript errors

### Popup not showing?
1. Clear browser cache
2. Check if `$unreadNotifications` is being passed to the view
3. Verify the notification data structure is correct

### Mark as read not working?
1. Check CSRF token is present
2. Verify the route exists and is accessible
3. Check user permissions

## Future Enhancements

Potential improvements:
- Real-time notifications using WebSockets/Pusher
- Email notifications for critical messages
- SMS notifications
- Notification preferences/settings
- Notification categories and filtering
- Push notifications for mobile devices
