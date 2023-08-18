<?php
namespace App\Repositories\Client;

use App\Models\Product;

class ProductRepository
{
    protected $product;
    function __construct(Product $product)
    {
        $this->product = $product;
    }

}

?>