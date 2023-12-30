<?php

namespace App\Models;

use App\Models\Image;
use App\Models\Category;
use App\Models\ProductImagePivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'parent_category_id', 'sub_category_id', 'image', 'price', 'qty', 'status', 'user_id'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * Sub category to User relationship with hasOne
     */
    public function getCatUserHasOne()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /*
    * get Products all related images from ProductImagePivot Table
    */
    public function getProductImagesHasMany()
    {
        return $this->hasMany(ProductImagePivot::class, 'product_id','id');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class)->withPivot('sub_category_id');
    }
}