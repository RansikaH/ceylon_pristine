# Admin Notification Interface - Example Implementation

## Quick Admin Panel for Sending Notifications

If you want to create an admin panel to send notifications to users, here's a simple example:

### 1. Create Admin Controller Method

Add to `app/Http/Controllers/Admin/NotificationController.php`:

```php
public function sendToUser(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:info,success,warning,error',
        'url' => 'nullable|url'
    ]);

    $user = \App\Models\User::findOrFail($request->user_id);
    
    $user->notify(new \App\Notifications\UserNotification(
        $request->title,
        $request->message,
        $request->url ?? url('/dashboard'),
        $request->type
    ));

    return redirect()->back()->with('success', 'Notification sent to ' . $user->name);
}

public function sendToAll(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
        'type' => 'required|in:info,success,warning,error',
        'url' => 'nullable|url'
    ]);

    $users = \App\Models\User::where('role', 'user')->get();
    
    foreach ($users as $user) {
        $user->notify(new \App\Notifications\UserNotification(
            $request->title,
            $request->message,
            $request->url ?? url('/dashboard'),
            $request->type
        ));
    }

    return redirect()->back()->with('success', 'Notification sent to ' . $users->count() . ' users');
}
```

### 2. Add Routes

Add to `routes/web.php` in the admin section:

```php
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... existing routes ...
    
    Route::get('/send-notification', [App\Http\Controllers\Admin\NotificationController::class, 'showSendForm'])
        ->name('notifications.send.form');
    Route::post('/send-notification/user', [App\Http\Controllers\Admin\NotificationController::class, 'sendToUser'])
        ->name('notifications.send.user');
    Route::post('/send-notification/all', [App\Http\Controllers\Admin\NotificationController::class, 'sendToAll'])
        ->name('notifications.send.all');
});
```

### 3. Create Admin View

Create `resources/views/admin/notifications/send.blade.php`:

```blade
@extends('admin.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-send me-2"></i>Send Notification to Users</h4>
                </div>
                <div class="card-body">
                    
                    <!-- Send to Specific User -->
                    <h5 class="mb-3">Send to Specific User</h5>
                    <form action="{{ route('admin.notifications.send.user') }}" method="POST" class="mb-5">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Select User</label>
                            <select name="user_id" class="form-select" required>
                                <option value="">Choose a user...</option>
                                @foreach(\App\Models\User::where('role', 'user')->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required 
                                   placeholder="e.g., Order Shipped">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="3" required
                                      placeholder="Enter your message here..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="info">Info (Blue)</option>
                                <option value="success">Success (Green)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="error">Error (Red)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Link URL (Optional)</label>
                            <input type="url" name="url" class="form-control" 
                                   placeholder="https://example.com/page">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-2"></i>Send to User
                        </button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <!-- Send to All Users -->
                    <h5 class="mb-3">Send to All Users</h5>
                    <form action="{{ route('admin.notifications.send.all') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required 
                                   placeholder="e.g., Special Announcement">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="3" required
                                      placeholder="Enter your message here..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select" required>
                                <option value="info">Info (Blue)</option>
                                <option value="success">Success (Green)</option>
                                <option value="warning">Warning (Yellow)</option>
                                <option value="error">Error (Red)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Link URL (Optional)</label>
                            <input type="url" name="url" class="form-control" 
                                   placeholder="https://example.com/page">
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            This will send the notification to ALL users!
                        </div>
                        
                        <button type="submit" class="btn btn-warning" 
                                onclick="return confirm('Send notification to all users?')">
                            <i class="bi bi-broadcast me-2"></i>Send to All Users
                        </button>
                    </form>
                    
                </div>
            </div>
            
            <!-- Quick Templates -->
            <div class="card shadow mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-file-text me-2"></i>Quick Templates</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h6 class="text-success">Order Shipped</h6>
                                <small class="text-muted">
                                    <strong>Title:</strong> Order Shipped<br>
                                    <strong>Message:</strong> Your order has been shipped and is on its way!<br>
                                    <strong>Type:</strong> Success
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h6 class="text-info">New Products</h6>
                                <small class="text-muted">
                                    <strong>Title:</strong> New Products Available<br>
                                    <strong>Message:</strong> Check out our latest collection!<br>
                                    <strong>Type:</strong> Info
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h6 class="text-warning">Payment Reminder</h6>
                                <small class="text-muted">
                                    <strong>Title:</strong> Payment Pending<br>
                                    <strong>Message:</strong> Please complete your payment to process your order.<br>
                                    <strong>Type:</strong> Warning
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border p-3 rounded">
                                <h6 class="text-primary">Welcome Message</h6>
                                <small class="text-muted">
                                    <strong>Title:</strong> Welcome!<br>
                                    <strong>Message:</strong> Thank you for joining CeylonMoms!<br>
                                    <strong>Type:</strong> Info
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

### 4. Add to Admin Navigation

Add a link in your admin navigation menu:

```blade
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.notifications.send.form') }}">
        <i class="bi bi-send"></i> Send Notifications
    </a>
</li>
```

## Usage

1. Admin logs in
2. Navigates to "Send Notifications" page
3. Fills out the form:
   - Select user (or send to all)
   - Enter title and message
   - Choose notification type
   - Optionally add a link
4. Click "Send"
5. Users will see the notification on their dashboard

## Alternative: Quick Send from User List

Add a "Send Notification" button next to each user in your user management page:

```blade
<a href="{{ route('admin.notifications.send.form', ['user_id' => $user->id]) }}" 
   class="btn btn-sm btn-outline-primary">
    <i class="bi bi-send"></i> Send Notification
</a>
```

This provides a quick way to send notifications while viewing the user list.
