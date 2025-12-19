<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'cost',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchase_items';

    /**
     * Get the purchase that owns this item.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the product associated with this purchase item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}