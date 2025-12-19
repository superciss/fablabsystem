<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    // Fillable fields for mass assignment
    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'delivery_type',
        'delivery_status',
        'total_amount',
        'approve_by_admin',
        'discount', 
        'tax', 
        'estimate_date',
        'type_request',
        'grand_total',
        'amount_paid', 
        'change_due',
        'created_at',
    ];
    
    //  protected static function booted()
    // {
    //     static::updated(function ($order) {
    //         // Check if status changed to 'completed'
    //         if ($order->isDirty('status') && $order->status === 'completed') {
    //             Mail::to($order->user->email)->send(new OrderCompletedMail($order));
    //         }
    //     });
    // }


    // Relationship with User (customer)

    public function customizedProducts()
{
    return $this->hasMany(Custom::class);
}

  public function custom()
    {
        return $this->customizedProducts();
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

     public function orderitem()
    {
        return $this->hasMany(OrderItem::class);
    }

     public function notification()
    {
        return $this->hasOne(Notification::class);
    }
    
}
