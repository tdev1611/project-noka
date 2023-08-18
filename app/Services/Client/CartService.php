<?php
namespace App\Services\Client;

use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart as SessionCart;
use App\Repositories\Client\CartRepository;

class CartService
{
    
    // cart to session
    function getSessionCart()
    {
       
        return SessionCart::content();
    }
    function CountSessionCart()
    {
        return SessionCart::count();
    }
    //add cart to session
    function addSessionCart()
    {
        $id = request()->input('id');
        $qty = request()->input('qty');
        $color = request()->input('color');
        $size = request()->input('size');
        $product = Product::find($id);
        if (!$product) {
            throw new \Exception('Product not found');
        }
        $slug = $product->slug;
        $cart = SessionCart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'options' => [
                'image' => $product->image,
                'color' => $color,
                'size' => $size,
                'slug' => $slug,
            ]
        ]);
        return $cart;

    }

    // ----------------------------------------------------------------------------
// cart to db
    function getCartByUser()
    {
        return Cart::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
    }
    function getCartByRowId($rowId)
    {
        $cart = Cart::where('rowId', $rowId)->first();
        return $cart;
    }
    function CartCountDb()
    {
        return Cart::where('user_id', Auth::user()->id)->sum('qty');
    }

    function addCartDb()
    {
        $id = request()->input('id');
        $qty = request()->input('qty');
        $color = request()->input('color');
        $size = request()->input('size');
        $product = Product::find($id);
        if (!$product) {
            throw new \Exception('Product not found');
        }
        $slug = $product->slug;
        $rowId = md5($color . $size);
        $existItem = Cart::where('rowId', $rowId)
            ->where('user_id', Auth::user()->id)
            ->first();
        if ($existItem) {
            $existItem->qty += $qty;
            $existItem->subtotal = $existItem->qty * $product->price;
            return $existItem->save();
        }
        $cart = Cart::create([
            'rowId' => $rowId,
            'product_id' => $product->id,
            'user_id' => Auth::user()->id,
            'name' => $product->name,
            'qty' => $qty,
            'subtotal' => $qty * $product->price,
            'price' => $product->price,
            'options' => json_encode([
                'image' => $product->image,
                'color' => $color,
                'size' => $size,
                'slug' => $slug,
            ])
        ]);
        return $cart;
    }

}


?>