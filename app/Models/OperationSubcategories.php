<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationSubcategories extends Model
{
    use HasFactory;

    protected $fillable = ['operation_id', 'subcategory_id', 'user_id_subcategory'];

    public function subcategoryToAssign()
    {
        return $this->belongsTo(SubcategoryToAssign::class, 'subcategory_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id_subcategory');
    }

public function operationSubcategories()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }


}
