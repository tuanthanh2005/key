<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'image_path',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
    ];

    /**
     * Scope lọc bài viết đã xuất bản
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
