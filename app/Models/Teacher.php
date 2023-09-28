<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
     protected $fillable = [
        'name', 'email','phone','user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
