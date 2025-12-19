<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Custom extends Model
{
    use HasFactory;

     protected $table = 'customized_products';

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'front_image',
        'back_image',
        'description',
        'approved',
        'total_price',
        'quantity',
        'payment_type',
        'partial_amount',
    ];

    // protected $casts = [
    //     'image' => 'binary',
    // ];

    public function order()
{
    return $this->belongsTo(Order::class);
}


     public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function backImage()
{
    return $this->hasOne(CustomBackImage::class, 'customized_product_id');
}

}
