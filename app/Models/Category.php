<?php

namespace App\Models;

use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'status', 'user_id'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at', 'updated_at'];
    // CHANGESTATUS Constant
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    // parent category's related sub category
    public function subcategories()
    {
        return $this->belongsToMany(SubCategory::class);
    }

    // get category's related products
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('sub_category_id');
    }
}