<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // If your table name doesn't follow Laravel pluralization
    protected $table = 'inventory';

    // Fields that are mass assignable
    protected $fillable = [
        'product_id',
        'machine_id',
        'order_id',
        'remarks',
    ];

    /**
     * Relationship: InventoryLog belongs to Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

     public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

     public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
