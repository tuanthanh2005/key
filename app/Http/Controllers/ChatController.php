<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Get chat messages for the current session.
     */
    public function getMessages(Request $request)
    {
        $sessionId = $request->input('session_id');

        if (!$sessionId) {
            return response()->json(['success' => false, 'messages' => []]);
        }

        // Single DB query to get all messages for this session
        $allMessages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'asc')
            ->get();

        $unreadAdminCount = 0;
        $consecutiveCustomerCount = 0;

        // Compute unread admin count
        foreach ($allMessages as $msg) {
            if ($msg->sender_type === 'admin' && !$msg->is_read) {
                $unreadAdminCount++;
            }
        }

        // Compute consecutive customer count from end
        for ($i = $allMessages->count() - 1; $i >= 0; $i--) {
            if ($allMessages[$i]->sender_type === 'customer') {
                $consecutiveCustomerCount++;
            } else {
                break;
            }
        }

        $messages = $allMessages->map(function ($msg) {
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
            'unread_admin_count' => $unreadAdminCount,
            'consecutive_customer_count' => $consecutiveCustomerCount,
            'can_send' => ($consecutiveCustomerCount < 5),
        ]);
    }

    /**
     * Mark admin messages as read by customer.
     */
    public function markAsRead(Request $request)
    {
        $sessionId = $request->input('session_id');
        if ($sessionId) {
            ChatMessage::where('session_id', $sessionId)
                ->where('sender_type', 'admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Customer sends a message (text and/or image).
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

        $sessionId = $request->input('session_id');

        // Check 1: 5-second cooldown between customer messages
        $lastCustomerMsg = ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'customer')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCustomerMsg && $lastCustomerMsg->created_at->diffInSeconds(now()) < 5) {
            $secondsLeft = 5 - $lastCustomerMsg->created_at->diffInSeconds(now());
            if ($secondsLeft < 1) $secondsLeft = 1;
            return response()->json([
                'success' => false,
                'message' => "Vui lòng chờ {$secondsLeft}s nữa để gửi tin nhắn tiếp theo.",
            ], 429);
        }

        // Check 2: Max 5 consecutive customer messages without admin reply
        $recentMsgs = ChatMessage::where('session_id', $sessionId)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        $consecutiveCount = 0;
        foreach ($recentMsgs as $msg) {
            if ($msg->sender_type === 'customer') {
                $consecutiveCount++;
            } else {
                break;
            }
        }

        if ($consecutiveCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã gửi 5 tin nhắn liên tiếp. Vui lòng chờ Admin phản hồi trước khi nhắn tiếp.',
            ], 429);
        }

        $userId = auth()->check() ? auth()->id() : null;
        $senderName = auth()->check() ? auth()->user()->name : 'Khách hàng #' . substr($sessionId, 0, 6);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/chat_images', 'public_uploads');
        }

        $chatMsg = ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => $userId,
            'sender_type' => 'customer',
            'sender_name' => $senderName,
            'message' => $request->input('message'),
            'image_path' => $imagePath,
            'is_read' => false,
        ]);

        // Send Telegram Notification to Admin
        $this->notifyTelegramAdmin($chatMsg, $sessionId);

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

    /**
     * Notify Admin via Telegram Bot when customer sends a message.
     */
    private function notifyTelegramAdmin(ChatMessage $chatMsg, string $sessionId)
    {
        try {
            $botToken = Setting::get('telegram_bot_token', env('TELEGRAM_BOT_TOKEN'));
            $chatId = Setting::get('telegram_chat_id', env('TELEGRAM_CHAT_ID'));

            if ($botToken && $chatId) {
                $adminChatUrl = route('admin.chat.index', ['session' => $sessionId]);

                $text = "💬 *TIN NHẮN HỖ TRỢ MỚI*\n\n"
                      . "👤 *Khách hàng*: `{$chatMsg->sender_name}`\n"
                      . "🆔 *Session*: `{$sessionId}`\n";

                if ($chatMsg->message) {
                    $text .= "📝 *Nội dung*: {$chatMsg->message}\n";
                }

                if ($chatMsg->image_path) {
                    $text .= "🖼️ *Đính kèm*: Có hình ảnh\n";
                }

                $text .= "\n👉 [Click vào đây để trả lời khách]({$adminChatUrl})";

                $fullImagePath = null;
                if ($chatMsg->image_path) {
                    if (file_exists(public_path($chatMsg->image_path))) {
                        $fullImagePath = public_path($chatMsg->image_path);
                    } elseif (Storage::disk('public')->exists($chatMsg->image_path)) {
                        $fullImagePath = Storage::disk('public')->path($chatMsg->image_path);
                    }
                }

                if ($fullImagePath && file_exists($fullImagePath)) {
                    // Send photo if available
                    Http::attach(
                        'photo', file_get_contents($fullImagePath), basename($fullImagePath)
                    )->post("https://api.telegram.org/bot{$botToken}/sendPhoto", [
                        'chat_id' => $chatId,
                        'caption' => $text,
                        'parse_mode' => 'Markdown',
                    ]);
                } else {
                    Http::timeout(3)->post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $text,
                        'parse_mode' => 'Markdown',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Telegram Chat notification error: ' . $e->getMessage());
        }
    }
}
