<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'reply_subject',
        'reply_content',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}
