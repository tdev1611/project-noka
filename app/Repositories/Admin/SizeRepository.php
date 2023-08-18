<?php
namespace App\Services\Admin;

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