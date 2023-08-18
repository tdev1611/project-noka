<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $fillable = [
        'rowId',
        'user_id',
        'product_id',
        'name',
        'price',
        'qty',
        'subtotal',
        'options',
    ];

}