<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'operation_id',
        'budget_id',
        'category_id',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
