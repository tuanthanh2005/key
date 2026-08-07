<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'sender_type',
        'sender_name',
        'message',
        'image_path',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image_path)) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }

        if (str_starts_with($this->image_path, 'uploads/') || str_starts_with($this->image_path, 'chat_images/')) {
            if (file_exists(public_path($this->image_path))) {
                return asset($this->image_path);
            }
            if (file_exists(storage_path('app/public/' . $this->image_path))) {
                return asset('storage/' . $this->image_path);
            }
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }
}
