<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSubcategories extends Model
{
    use HasFactory;

    protected $fillable = ['operation_id', 'subcategory_id', 'user_id_subcategory', 'user_id_admin'];

    public function subcategoryToAssign()
    {
        return $this->belongsTo(SubcategoryToAssign::class, 'subcategory_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id_subcategory');
    }


    public function usersAdmin()
    {
        return $this->belongsTo(User::class, 'user_id_admin');
    }
}
