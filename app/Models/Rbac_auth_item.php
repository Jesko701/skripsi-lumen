<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Rbac_auth_item extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'rbac_auth_item';
    protected $dateFormat = 'U'; 
    protected $primaryKey = 'name';
    protected $fillable = [
        'name', 'type','description','rule_name','data','created_at','updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public function rbac_auth_rule(){
        return $this->belongsTo(Rbac_auth_rule::class,"rule_name","name");
    }
    public function rbac_auth_item_children(){
        return $this->hasMany(Rbac_auth_item_child::class,"parent");
    }
    public function rbac_auth_assignment(){
        return $this->hasMany(Rbac_auth_assignment::class,"item_name");
    }

}
