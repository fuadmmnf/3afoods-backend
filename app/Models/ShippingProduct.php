<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'business_name',
        'avn',
        'contact_info',
        'website_name',
        'file',
        'additional_information',
    ];
}
