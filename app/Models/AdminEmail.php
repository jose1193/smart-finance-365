<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEmail extends Model
{
    use HasFactory;
     protected $fillable = [
        'name_support',
        'email', 
         'user_id'
    ];

     public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
