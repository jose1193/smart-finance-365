<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $fillable = ['subcategory_name', 'category_id','user_id'];

   
    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function subcategoryToAssign()
    {
        return $this->hasMany(SubcategoryToAssign::class, 'subcategory_id');
    }

    public function assignedUsersSubcategory()
    {
        return $this->belongsToMany(User::class, 'subcategory_to_assigns', 'subcategory_id', 'user_id_subcategory');
    }

     // Relación hasMany con OperationSubcategories
    public function operationSubcategories()
    {
        return $this->hasMany(OperationSubcategories::class, 'subcategory_id');
    }
    
     // Relación hasMany con OperationSubcategories
    public function operationProcessSubcategories()
    {
        return $this->hasMany(ProcessOperationSubcategories::class, 'subcategory_id');
    }
}
