<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomBackImage extends Model
{
    use HasFactory;

     protected $table = 'customized_product_back_img';

    protected $fillable = [
       'customized_product_id',
        'back_img',
       
    ];

    // protected $casts = [
    //     'image' => 'binary',
    // ];

  public function customizedProduct()
{
    return $this->belongsTo(Custom::class, 'customized_product_id');
}

}
