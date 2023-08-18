<?php
namespace App\Services\Client;

use App\Repositories\Client\CartRepository;


class CartService
{
    protected $cartRepository;
    function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    // cart to session
    function getSessionCart()
    {
        return $this->cartRepository->getCartSessionAll();
    }
    function CountSessionCart()
    {
        return $this->cartRepository->CountSessionCart();
    }
    //add cart to session
    function addSessionCart()
    {
        return $this->cartRepository->addCartSession();

    }
    //update item in cart 
    function updateCartSessions()
    {
        $item = $this->cartRepository->updateCartSessions();
        return $item;
    }

    // ----------------------------------------------------------------------------
// cart to db
    function getCartByUser()
    {
        return $this->cartRepository->getCartByUserId();
    }
    function getCartById($rowId)
    {
        return $this->cartRepository->getCartById($rowId);
    }
    function CartCountDb()
    {
        return $this->cartRepository->CartCountDb();
    }

    function addCartDb()
    {
        $item = $this->cartRepository->addCartDb();
        return $item;
    }
    function updateCartDb()
    {
        $updateItem = $this->cartRepository->updateCartDb();
        if ($updateItem === null) {
            throw new \Exception('Product not found');
        }
        return $updateItem;
    }
    function deleteCartDb($id)
    {
        $item = $this->cartRepository->deleteCartDb($id);
        if ($item === null) {
            throw new \Exception('Product not found');
        }
        return $item;
    }


}


?>