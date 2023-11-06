<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoryToAssign extends Model
{
    use HasFactory;

    protected $fillable = ['subcategory_id ','user_id_subcategory','user_id_admin'];

    public function subCategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
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
