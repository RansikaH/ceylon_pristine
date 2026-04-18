<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Auth::guard('admin')->user()->notifications()->paginate(10);
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Get the latest notifications for the admin.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        $notifications = Auth::guard('admin')->user()
            ->unreadNotifications()
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'data' => $notification->data,
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark the specified notification as read.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function markAsRead($id)
    {
        $notification = Auth::guard('admin')->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified notification from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Auth::guard('admin')->user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}
