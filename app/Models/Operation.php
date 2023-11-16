<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;
    protected $fillable = [
        'operation_description',
        'operation_amount',
        'operation_currency',
        'operation_currency_total',
        'operation_status',
        'operation_date',
        'operation_month',
        'operation_year',
        'category_id',
        'user_id',
    ];
   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Agrega la relación con la categoría de ingreso si no la has hecho aún
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(StatuOptions::class, 'operation_status');
    }

    public function operationSubcategories()
    {
        return $this->hasMany(OperationSubcategories::class, 'operation_id');
    }
}
