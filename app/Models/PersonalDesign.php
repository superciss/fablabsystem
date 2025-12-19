<?php

// app/Models/PersonalDesign.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalDesign extends Model
{
      protected $table = 'personal_design';

    protected $fillable = ['user_id', 'image_design', 'approved', 'description', 'total_price', 'design_status', 'estimate_date_design', 'deliver'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}