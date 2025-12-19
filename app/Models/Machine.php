<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCompletedMail;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machine_product';

    // Fillable fields for mass assignment
    protected $fillable = [
        'machine_name',
        'brand',
        'status',
        'property_no',
        'cost',
        'created_at',
        'supplier_id',
    ];

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class);
    }

}
