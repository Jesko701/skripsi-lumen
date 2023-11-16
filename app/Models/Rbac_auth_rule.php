<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Rbac_auth_rule extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'rbac_auth_rule';
    protected $dateFormat = 'U'; 
    protected $primaryKey = "name";
    protected $fillable = [
        'name', 'data','created_at','updated_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    public function rbac_auth_item(){
        return $this->hasMany(Rbac_auth_item::class,"rule_name");
    }

    public function getFormattedDataAttribute(){
        if (is_resource($this->data)){
            return base64_encode(stream_get_contents($this->data));
        }
        return $this->data;
    }
}
