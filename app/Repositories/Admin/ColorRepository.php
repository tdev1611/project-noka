<?php
namespace App\Services\Admin;

use App\Models\Color;

class ColorRepository
{
    protected $color;
    function __construct(Color $color)
    {
        $this->color = $color;
    }

}

?>