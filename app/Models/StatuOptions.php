<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatuOptions extends Model
{
    use HasFactory;
     protected $fillable = [
         'main_category_id',
        'status_description'
        
    ];

     public function mainCategory()
    {
        return $this->belongsTo(MainCategories::class, 'main_category_id');
    }

    public function generatedOperation()
    {
        return $this->hasMany(GeneratedOperation::class, 'operation_status');
    }
}
