<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
protected $fillable = [
        'category_name', 'category_description','main_category_id','user_id',
    ];

public function income()
    {
        return $this->hasMany(Income::class);
    }

    public function operation()
    {
        return $this->hasMany(Operation::class);
    }

    public function mainCategories()
    {
        return $this->belongsTo(MainCategories::class, 'main_category_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function categoryToAssign()
    {
        return $this->hasMany(CategoriesToAssign::class, 'category_id');
    }

    public function Subcategory()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }

    
    public function assignedUsers() {
    return $this->belongsToMany(User::class, 'categories_to_assigns', 'category_id', 'user_id_assign');
    }

    public function budgetExpenses()
    {
        return $this->hasMany(BudgetExpense::class);
    }

     public function budgetIncomes()
    {
        return $this->hasMany(BudgetIncome::class);
    }
}
