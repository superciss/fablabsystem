<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'order_id',
        'message',
        'is_read',
    ];


    // Notification belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
