<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'image',
        'list_image',
        'desc',
        'detail',
        'status',

    ];

    function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');

    }

    function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id');

    }

}