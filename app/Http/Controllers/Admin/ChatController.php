<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Display Admin Chat View.
     */
    public function index(Request $request)
    {
        $selectedSession = $request->query('session');
        return view('admin.chat.index', compact('selectedSession'));
    }

    /**
     * Get list of all chat sessions for Admin sidebar.
     */
    public function getSessions()
    {
        $sessions = ChatMessage::select('session_id', DB::raw('MAX(created_at) as last_activity'))
            ->groupBy('session_id')
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($item) {
                $lastMsg = ChatMessage::where('session_id', $item->session_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $unreadCount = ChatMessage::where('session_id', $item->session_id)
                    ->where('sender_type', 'customer')
                    ->where('is_read', false)
                    ->count();

                $customerName = ChatMessage::where('session_id', $item->session_id)
                    ->where('sender_type', 'customer')
                    ->value('sender_name') ?? 'Khách hàng #' . substr($item->session_id, 0, 6);

                return [
                    'session_id' => $item->session_id,
                    'customer_name' => $customerName,
                    'last_message' => $lastMsg ? ($lastMsg->message ?: '[Hình ảnh]') : '',
                    'unread_count' => $unreadCount,
                    'last_activity' => $lastMsg ? $lastMsg->created_at->diffForHumans() : '',
                ];
            });

        $totalUnread = ChatMessage::where('sender_type', 'customer')->where('is_read', false)->count();

        return response()->json([
            'success' => true,
            'sessions' => $sessions,
            'total_unread' => $totalUnread,
        ]);
    }

    /**
     * Get conversation messages for a specific session ID & mark as read.
     */
    public function getMessages($sessionId)
    {
        // Mark customer messages as read
        ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_type' => $msg->sender_type,
                    'sender_name' => $msg->sender_name,
                    'message' => $msg->message,
                    'image_url' => $msg->image_url,
                    'is_read' => $msg->is_read,
                    'created_at' => $msg->created_at->format('H:i d/m'),
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Admin sends reply to customer.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|max:100',
            'message' => 'nullable|string|max:2000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if (!$request->filled('message') && !$request->hasFile('image')) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập nội dung tin nhắn hoặc chọn hình ảnh.',
            ], 422);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/chat_images', 'public_uploads');
        }

        $adminName = auth()->user()->name ?? 'Admin Support';

        $chatMsg = ChatMessage::create([
            'session_id' => $request->input('session_id'),
            'user_id' => auth()->id(),
            'sender_type' => 'admin',
            'sender_name' => $adminName,
            'message' => $request->input('message'),
            'image_path' => $imagePath,
            'is_read' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $chatMsg->id,
                'sender_type' => $chatMsg->sender_type,
                'sender_name' => $chatMsg->sender_name,
                'message' => $chatMsg->message,
                'image_url' => $chatMsg->image_url,
                'is_read' => $chatMsg->is_read,
                'created_at' => $chatMsg->created_at->format('H:i d/m'),
            ]
        ]);
    }
}
