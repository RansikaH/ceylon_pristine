# Quick Notification Reference

## Send Notification to a User

```php
use App\Models\User;
use App\Notifications\UserNotification;

$user = User::find($userId);
$user->notify(new UserNotification(
    'Title',
    'Message',
    url('/link'),  // optional
    'success'      // info|success|warning|error
));
```

## Test Routes (Admin Only)

### Send to specific user:
```
/test-user-notification/{userId}
```

### Send to all users:
```
/test-user-notification-all
```

## Notification Types

- `info` - Blue (general information)
- `success` - Green (confirmations)
- `warning` - Yellow (warnings)
- `error` - Red (errors)

## Quick Examples

### Order Update
```php
$user->notify(new UserNotification(
    'Order Shipped',
    'Your order #' . $order->id . ' is on its way!',
    route('orders.show', $order),
    'success'
));
```

### Promotion
```php
$user->notify(new UserNotification(
    'Special Offer',
    'Get 20% off this weekend!',
    route('shop.full'),
    'info'
));
```

### Alert
```php
$user->notify(new UserNotification(
    'Payment Failed',
    'Please update your payment method.',
    route('profile.edit'),
    'error'
));
```

## What Happens?

1. ✅ Popup alert appears on user's dashboard
2. ✅ Shows in "Pending Messages" section
3. ✅ Alert banner shows unread count
4. ✅ User can mark as read or dismiss
