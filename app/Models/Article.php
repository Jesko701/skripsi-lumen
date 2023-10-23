<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Article extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'article';
    protected $fillable = [
        'slug', 'title', 'body', 'view', 'category_id', 'thumbnail_base_url', 'thumbnail_path', 'status', 'created_by', 'updated_by', 'published_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
    public function article_category()
    {
        return $this->belongsTo(Article_category::class, 'category_id', 'id');
    }
    public function article_attachment()
    {
        return $this->hasMany(Article_attachment::class);
    }
}
