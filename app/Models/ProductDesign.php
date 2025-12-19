<?php

// app/Models/ProductDesign.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDesign extends Model
{
      protected $table = 'product_designs';

    protected $fillable = ['user_id', 'product_id', 'name', 'design_data', 'thumbnail'];
    
    protected $casts = [
        'design_data' => 'array'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}