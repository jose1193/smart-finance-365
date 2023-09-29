<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_description',
        'expense_amount',
        'expense_date',
        'expense_month',
        'expense_year',
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
}
