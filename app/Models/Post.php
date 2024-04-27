<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_title',
        'post_content',
        'post_image',
        'post_status',
        'post_date',
        'meta_description',
        'meta_title',
        'meta_keywords',
        'post_title_slug',
        'user_id',
        'category_id',
    ];
    
        public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
