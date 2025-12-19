<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Texture extends Model
{
    use HasFactory;

    protected $table = 'textures';
    
    protected $fillable = [
        'name',
        'image',
        'category',
        'price',
        'size',
    ];


    public function options()
    {
        return $this->hasMany(ProductOption::class);
    }
}

