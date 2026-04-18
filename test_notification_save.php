<?php

// Simple test script to verify notification saving
// Run this from command line: php test_notification_save.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\DB;

echo "=== Notification System Test ===\n\n";

// Check if notifications table exists
try {
    $tableExists = DB::select("SHOW TABLES LIKE 'notifications'");
    if (empty($tableExists)) {
        echo "❌ ERROR: notifications table does not exist!\n";
        echo "   Run: php artisan migrate\n\n";
        exit(1);
    }
    echo "✅ Notifications table exists\n";
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Get first user
try {
    $user = User::where('role', 'user')->first();
    if (!$user) {
        echo "❌ ERROR: No regular users found in database\n";
        echo "   Create a user first\n\n";
        exit(1);
    }
    echo "✅ Found user: {$user->name} (ID: {$user->id})\n";
} catch (Exception $e) {
    echo "❌ Error finding user: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Count existing notifications
$beforeCount = DB::table('notifications')
    ->where('notifiable_id', $user->id)
    ->count();
echo "📊 Existing notifications for this user: {$beforeCount}\n\n";

// Send test notification
echo "📤 Sending test notification...\n";
try {
    $user->notify(new UserNotification(
        'Test Notification',
        'This is a test message to verify the notification system is working correctly.',
        url('/dashboard'),
        'info'
    ));
    echo "✅ Notification sent successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to send notification: " . $e->getMessage() . "\n";
    echo "   Stack trace: " . $e->getTraceAsString() . "\n\n";
    exit(1);
}

// Wait a moment for database write
sleep(1);

// Check if notification was saved
$afterCount = DB::table('notifications')
    ->where('notifiable_id', $user->id)
    ->count();

echo "📊 Notifications after sending: {$afterCount}\n";

if ($afterCount > $beforeCount) {
    echo "✅ SUCCESS! Notification was saved to database\n\n";
    
    // Show the latest notification
    $latest = DB::table('notifications')
        ->where('notifiable_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();
    
    echo "Latest notification details:\n";
    echo "  ID: {$latest->id}\n";
    echo "  Type: {$latest->type}\n";
    echo "  Data: {$latest->data}\n";
    echo "  Created: {$latest->created_at}\n";
    echo "  Read: " . ($latest->read_at ? $latest->read_at : 'Unread') . "\n\n";
    
    echo "✅ ALL TESTS PASSED!\n";
    echo "The notification system is working correctly.\n\n";
} else {
    echo "❌ FAILED! Notification was NOT saved to database\n";
    echo "   Check your queue configuration\n";
    echo "   The notification might be queued. Run: php artisan queue:work\n\n";
}
