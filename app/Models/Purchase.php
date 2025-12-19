<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supplier_id',
        'total_cost',
        'purchase_date',
         'status', 
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'purchases';

    /**
     * Get the supplier associated with the purchase.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the purchase items for this purchase.
     * (Assuming you'll have a purchase_items table)
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    //  public function getBalanceAttribute()
    // {
    //     return match($this->status) {
    //         'unpaid'  => $this->total_cost,
    //         'partial' => $this->total_cost / 2,
    //         'paid'    => 0,
    //         default   => $this->total_cost,
    //     };
    // }
    
}