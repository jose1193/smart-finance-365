<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessBudgetIncome extends Model
{
    use HasFactory;
    protected $fillable = [
        'process_operation_id',
        'budget_id',
        'category_id',
    ];

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function processOperation()
    {
        return $this->belongsTo(ProcessOperation::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
