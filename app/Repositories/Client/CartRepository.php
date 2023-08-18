<?php
namespace App\Repositories\Client;

use App\Models\Cart;
use Gloudemans\Shoppingcart\Facades\Cart as SessionCart;
class CartRepository
{
    protected $cart;
    function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

}

?>