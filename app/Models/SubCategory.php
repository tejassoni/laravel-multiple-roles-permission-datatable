<?php

namespace App\Models;

use App\Models\User;
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
    // CHANGESTATUS Constant
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

     /**
     * Sub category to User relationship with hasOne
     */
    public function getCatUserHasOne()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    } 

    // Sub category's related Parent category
    public function parentcategories(){
        return $this->belongsToMany(Category::class);
    }
}