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
        return $this->color->where('status', 1)->oldest()->paginate(8);
    }
    // get by slug
    function getBySlug($slug)
    {
        $color = $this->color->where('slug', $slug)->first();
        if (!$color) {
            return null;
        }
        return $color;
    }
}

?>