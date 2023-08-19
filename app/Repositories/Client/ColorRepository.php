<?php
namespace App\Repositories\Client;

use App\Models\Color;

class ColorRepository
{
    protected $color;
    function __construct(Color $color)
    {
        $this->color = $color;
    }
    // bystatus =1 
    function getAll()
    {
        $categories = Color::where('status', 1)->orderBy('name', 'ASC')->paginate(8);
        return $categories;
    }
    // get by slug
    function getBySlug($slug)
    {
        $color = Color::where('slug', $slug)->first();
        if (!$color) {
            return null;
        }
        return $color;
    }
}

?>