<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = 'ratings';

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'image'
    ];

    /**
     * A rating belongs to a product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * A rating belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
