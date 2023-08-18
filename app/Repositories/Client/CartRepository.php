<?php
namespace App\Services\Admin;

use App\Models\Cart;

class ProductRepository
{
    protected $cart;
    function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

}

?>