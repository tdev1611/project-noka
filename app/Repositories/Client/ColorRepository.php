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

}

?>