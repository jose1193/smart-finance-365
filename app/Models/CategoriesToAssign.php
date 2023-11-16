<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesToAssign extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id',
        'user_id_assign', 
        'user_id_admin', 
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

     public function users()
    {
        return $this->belongsTo(User::class, 'user_id_assign');
    }
    public function usersAdmin()
    {
        return $this->belongsTo(User::class, 'user_id_admin');
    }

    
}
