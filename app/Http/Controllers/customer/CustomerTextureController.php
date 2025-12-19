<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Texture;

class CustomerTextureController extends Controller
{
    public function listJson()
    {
        return response()->json(Texture::latest()->get());
    }
}