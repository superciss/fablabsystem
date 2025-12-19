<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    // Fillable fields for mass assignment
    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'stock',
        'low_stock_threshold',
        'unit',
        'cost',
        'image',
        'pro_category',
        'is_customizable',
        'category_id',
        'supplier_id',
    ];

     public function customize()
    {
        return $this->hasMany(Custom::class);
    }
    
     public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
    
    // Define relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


     public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    
     public function purchaseitem()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function inventory()
    {
        return $this->hasMany(Inventory::class);
    }
    
    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartitem()
    {
        return $this->hasMany(CartItem::class);
 
    }

     public function design()
    {
        return $this->hasMany(ProductDesign::class);
    }

    public function ratings()
    {
        return $this->hasMany(\App\Models\Rating::class, 'product_id');
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function ratingPercentage($stars)
    {
        $total = $this->ratings()->count();
        if ($total == 0) return 0;

        $count = $this->ratings()->where('rating', $stars)->count();
        return round(($count / $total) * 100);
    }



}
