<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'contact_person',
        'email',
        'phone',
        'address',
    ];

    // A supplier can have many products
    public function product()
    {
        return $this->hasMany(Product::class);
    }

     public function products()
    {
        return $this->hasMany(Product::class);
    }


     public function purchase()
    {
        return $this->hasMany(Purchase::class);
    }
      public function machine()
    {
        return $this->hasMany(Machine::class);
    }
}
