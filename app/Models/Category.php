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

    public function mainCategories()
    {
        return $this->belongsTo(MainCategories::class);
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

}
