<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sub_categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'parent_category_id','status','user_id'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at','updated_at'];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    
    /**
     * Sub category to Parent category relationship with hasOne
     */
    public function getParentCatHasOne()
    {
        return $this->hasOne(Category::class, 'id', 'parent_category_id');
    }

    /**
     * Sub category to User relationship with hasOne
     */
    public function getCatUserHasOne()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }    
}