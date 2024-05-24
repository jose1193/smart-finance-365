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
        'process_operation_date_end',
        'operation_month',
        'operation_year',
        'category_id',
        'user_id',
        'process_operation_uuid',
        
        //'last_processed_at',
        
    ];
   public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Agrega la relación con la categoría de ingreso si no la has hecho aún
    public function category()
{
    return $this->belongsTo(Category::class);
}


    public function statuOption()
    {
        return $this->belongsTo(StatuOptions::class, 'operation_status');
    }

   
  public function processBudgetIncomes()
    {
        return $this->hasMany(ProcessBudgetIncome::class, 'process_operation_id');
    }

    public function processBudgetExpenses()
    {
        return $this->hasMany(ProcessBudgetExpense::class, 'process_operation_id');
    }

    public function operationProcessSubcategories()
{
    return $this->hasMany(ProcessOperationSubcategories::class, 'process_operation_id');
}

public function generatedOperations()
{
    return $this->hasMany(GeneratedOperation::class, 'process_operation_id');
}

public function latestGeneratedOperation()
    {
        return $this->hasOne(GeneratedOperation::class, 'process_operation_id')->latest('last_processed_at');
    }
   
}
