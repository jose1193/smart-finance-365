<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategories extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description',
    ];

    public function statusOptions()
    {
        return $this->hasMany(StatuOptions::class, 'main_category_id');
    }
    
}
