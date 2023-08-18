<?php
namespace App\Repositories\Client;

use App\Models\Size;

class ProductRepository
{
    protected $size;
    function __construct(Size $size)
    {
        $this->size = $size;
    }

}

?>