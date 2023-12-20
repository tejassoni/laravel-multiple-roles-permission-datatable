<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['order_code', 'total_amount', 'user_id'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['created_at','updated_at'];
    
    /**
     * Sub category to Parent category relationship with hasOne
     */
    public function getOrdersProductsHasMany()
    {
        return $this->hasMany(OrderProductPivot::class, 'order_id', 'id');
    }

    /**
     * Sub category to Parent category relationship with hasOne
     */
    public function getOrdersProductsHasManyThrough()
    {
        return $this->hasManyThrough(Product::class,OrderProductPivot::class,'order_id','id','product_id','product_id');       
    }

    public function products()
    {
      return $this->belongsToMany( Product::class,OrderProductPivot::class, 'order_id', 'product_id');
    }

    
    
}