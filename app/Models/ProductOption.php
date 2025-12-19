<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;

    
     protected $fillable = ['product_id', 'type', 'name', 'extra_price', 'texture_id'];

     
    protected $table = 'product_options';

   
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function texture()
    {
        return $this->belongsTo(Texture::class);
    }
}

