<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get admin user (assuming admin has role 'admin' or user with id 1)
        $admin = User::where('role', 'admin')->first() ?: User::find(1);
        
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not available for chat.');
        }

        // Ensure admin has proper avatar path
        if ($admin->avatar && !str_starts_with($admin->avatar, 'http')) {
            $admin->avatar = asset('storage/' . $admin->avatar);
        }

        $messages = Message::betweenUsers($user->id, $admin->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('receiver_id', $user->id)
            ->where('sender_id', $admin->id)
            ->unread()
            ->update(['is_read' => true]);

        return view('chat.index', compact('messages', 'admin'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $user = Auth::user();
        
        // Get admin user
        $admin = User::where('role', 'admin')->first() ?: User::find(1);
        
        if (!$admin) {
            return response()->json(['error' => 'Admin not available'], 404);
        }

        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $admin->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender')
        ]);
    }

    public function getMessages()
    {
        $user = Auth::user();
        $admin = User::where('role', 'admin')->first() ?: User::find(1);
        
        if (!$admin) {
            return response()->json(['messages' => []]);
        }

        $messages = Message::betweenUsers($user->id, $admin->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('receiver_id', $user->id)
            ->where('sender_id', $admin->id)
            ->unread()
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        $admin = User::where('role', 'admin')->first() ?: User::find(1);
        
        if (!$admin) {
            return response()->json(['count' => 0]);
        }

        $count = Message::where('receiver_id', $user->id)
            ->where('sender_id', $admin->id)
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    // Admin Chat Methods
    public function adminIndex()
    {
        $admin = Auth::user();
        
        // Get all unique conversations with admin
        $conversations = Message::where(function($query) use ($admin) {
            $query->where('sender_id', $admin->id)
                  ->orWhere('receiver_id', $admin->id);
        })
        ->with(['sender', 'receiver'])
        ->get()
        ->groupBy(function($message) use ($admin) {
            return $message->sender_id === $admin->id ? $message->receiver_id : $message->sender_id;
        })
        ->map(function($messages) {
            $lastMessage = $messages->last();
            $user = $lastMessage->sender_id === Auth::id() ? $lastMessage->receiver : $lastMessage->sender;
            $unreadCount = $messages->where('receiver_id', Auth::id())->where('is_read', false)->count();
            
            // Ensure user has proper avatar path for admin chat
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                $user->avatar = asset('storage/' . $user->avatar);
            }
            
            return [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount
            ];
        })
        ->sortByDesc(function($conversation) {
            return $conversation['last_message']->created_at;
        });

        return view('admin.chat.index', compact('conversations'));
    }

    public function adminChatWith($userId)
    {
        $admin = Auth::user();
        $customer = User::findOrFail($userId);

        $messages = Message::betweenUsers($admin->id, $customer->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('receiver_id', $admin->id)
            ->where('sender_id', $customer->id)
            ->unread()
            ->update(['is_read' => true]);

        return view('admin.chat.chat', compact('messages', 'customer'));
    }

    public function adminSend(Request $request, $userId)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $admin = Auth::user();
        $customer = User::findOrFail($userId);

        $message = Message::create([
            'sender_id' => $admin->id,
            'receiver_id' => $customer->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => $message->load('sender')
        ]);
    }

    public function adminGetMessages($userId)
    {
        $admin = Auth::user();
        $customer = User::findOrFail($userId);

        $messages = Message::betweenUsers($admin->id, $customer->id)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark unread messages as read
        Message::where('receiver_id', $admin->id)
            ->where('sender_id', $customer->id)
            ->unread()
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }

    public function adminGetConversations()
    {
        $admin = Auth::user();
        
        $conversations = Message::where(function($query) use ($admin) {
            $query->where('sender_id', $admin->id)
                  ->orWhere('receiver_id', $admin->id);
        })
        ->with(['sender', 'receiver'])
        ->get()
        ->groupBy(function($message) use ($admin) {
            return $message->sender_id === $admin->id ? $message->receiver_id : $message->sender_id;
        })
        ->map(function($messages) {
            $lastMessage = $messages->last();
            $user = $lastMessage->sender_id === Auth::id() ? $lastMessage->receiver : $lastMessage->sender;
            $unreadCount = $messages->where('receiver_id', Auth::id())->where('is_read', false)->count();
            
            // Ensure user has proper avatar path for admin conversations API
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                $user->avatar = asset('storage/' . $user->avatar);
            }
            
            return [
                'user' => $user,
                'last_message' => $lastMessage,
                'unread_count' => $unreadCount
            ];
        })
        ->sortByDesc(function($conversation) {
            return $conversation['last_message']->created_at;
        });

        return response()->json(['conversations' => $conversations->values()]);
    }
}
