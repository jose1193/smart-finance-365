<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessOperation extends Model
{
    use HasFactory;

     protected $fillable = [
        'operation_description',
        'operation_currency_type',
        'operation_amount',
        'operation_currency',
        'operation_currency_total',
        'operation_status',
        'operation_date',
        'process_operation_date',
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

   
  public function ProcessBudgetIncome()
    {
        return $this->hasMany(ProcessBudgetIncome::class, 'process_operation_id');
    }

    public function ProcessBudgetExpense()
    {
        return $this->hasMany(ProcessBudgetExpense::class, 'process_operation_id');
    }

    public function operationProcessSubcategories()
    {
        return $this->hasMany(ProcessOperationSubcategories::class, 'process_operation_id');
    }

    
}
