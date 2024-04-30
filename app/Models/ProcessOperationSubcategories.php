<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessOperationSubcategories extends Model
{
    use HasFactory;

     protected $fillable = ['process_operation_id', 'subcategory_id', 'user_id_subcategory'];

    public function subcategoryToAssign()
    {
        return $this->belongsTo(SubcategoryToAssign::class, 'subcategory_id', 'id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id_subcategory');
    }

    public function processOperationSubcategories()
    {
        return $this->belongsTo(ProcessOperation::class, 'process_operation_id');
    }

    // Nueva relación directa con Subcategory
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
    }
}
