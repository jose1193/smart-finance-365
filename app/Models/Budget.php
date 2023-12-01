<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;
    protected $fillable = [
        'budget_operation',
        'budget_currency_type',
        'budget_currency',
        'budget_currency_total',
        'budget_date',
        'budget_month',
        'budget_year',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(BudgetExpense::class);
    }

    

}
