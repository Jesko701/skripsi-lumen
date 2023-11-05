<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Formio_forms extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'formio_forms';
    protected $dateFormat = 'U';
    public $timestamps = false;
    protected $fillable = [
        'status','name', 'token', 'model', 'data','created_by','updated_by','deleted','id_tema','is_only_kordes','is_only_dosen','is_harus_login'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public function formio_submission(){
        return $this->hasMany(Formio_submission::class,'form_id');
    }
}
