<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'username',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

     public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
    public function category()
    {
        return $this->hasMany(Category::class);
    }

    public function income()
    {
        return $this->hasMany(Income::class);
    }
    public function expense()
    {
        return $this->hasMany(Expense::class);
    }
     public function operation()
    {
        return $this->hasMany(Operation::class);
    }
     public function emails()
    {
        return $this->hasMany(EmailManagement::class);
    }
    public function supportContact()
    {
        return $this->hasMany(SupportContactForm::class);
    }
    public function adminemail()
    {
        return $this->hasMany(AdminEmail::class, 'user_id');
    }

     public function categoryToAssign()
    {
        return $this->hasMany(CategoriesToAssign::class, 'user_id_assign');
    }

    public function categoryToAssignAdmin()
    {
        return $this->hasMany(CategoriesToAssign::class, 'user_id_admin');
    }
     
     public function Subcategory()
    {
        return $this->hasMany(Subcategory::class, 'user_id');
    }

    public function subcategoryToAssign()
    {
        return $this->hasMany(SubcategoryToAssign::class, 'user_id_subcategory');
    }

    public function subcategoryToAssignAdmin()
    {
        return $this->hasMany(SubcategoryToAssign::class, 'user_id_admin');
    }

    public function operationSubcategory()
    {
        return $this->hasMany(OperationSubcategories::class, 'user_id_subcategory');
    }

   public function categoriesAssigned()
    {
        return $this->belongsToMany(Category::class,  'user_id_assign', 'category_id');
    }

    public function subcategoriesAssigned()
    {
        return $this->belongsToMany(Subcategory::class, 'user_id_subcategory', 'subcategory_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
    
}
