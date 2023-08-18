<?php
namespace App\Services\Client;

use App\Models\Color;

class ColorService
{
    function getColors()
    {
        return Color::where('status', 1)->orderBy('name', 'asc')->get();
    }

    function find($slug)
    {
        $color = Color::where('slug', $slug)->first();
        if (!$color) {
            throw new \Exception('Not Found Color');
        }
        return $color;
    }
    function getProduct($slug)
    {
        $color = $this->find($slug);
        $products = $color->products()->paginate(8);
        return $products;
    }

}


?>