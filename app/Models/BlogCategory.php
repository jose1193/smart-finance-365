<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_name', 'blog_category_description','blog_category_image','user_id'
    ];

    public function user()
{
return $this->belongsTo(User::class);
}

 public function posts()
    {
        return $this->hasMany(Post::class); 
    }
}
