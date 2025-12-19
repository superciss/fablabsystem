<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'user_information';

    // Fillable fields for mass assignment
    protected $fillable = [
        'fullname',
        'user_id',
        'contact_number',
        'phone_verified',
        'phone_verification_code',
        'address',
        'degree',
        'year',
        'section',
        'gender',
        'photo',
    ];

   public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

    
}
