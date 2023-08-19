<?php
namespace App\Repositories\Client;

use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart as CartSession;

class CartRepository
{
    protected $cart, $cartSession;
    function __construct(Cart $cart, CartSession $cartSession)
    {
        $this->cart = $cart;
        $this->cartSession = $cartSession;
    }

    # Cart session
    // get all items
    public function getCartSessionAll()
    {
        return CartSession::content();
    }
    // count items 
    public function CountSessionCart()
    {
        return CartSession::count();
    }

    // add item to cart
    public function addCartSession()
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
        $item = $this->cartSession::add([
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
        return $item;
    }

    // update Cart
    function updateCartSessions()
    {
        $id = request()->input('rowId');
        $qty = request()->input('qty');
        $this->cartSession::update($id, $qty);
        $item = $this->cartSession::get($id);
        return $item;
    }

    // ----------------------------------------------------------------------------
    #CartDB
    // cartByUser
    public function getCartByUserId()
    {
        return $this->cart->where('user_id', Auth::user()->id)->latest()->get();
    }
    public function queryCartbyUser()
    {
        return $this->cart->where('user_id', Auth::user()->id);
    }
    // item by Id
    public function getCartById($rowId)
    {
        $cart = $this->cart->where('rowId', $rowId)->first();
        return $cart;
    }
    // count items in cart
    function CartCountDb()
    {
        $cart = $this->queryCartbyUser();
        return $cart->sum('qty');
    }

    // add item to cart
    public function addCartDb()
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

        $existItem = $this->queryCartbyUser()
            ->where('rowId', $rowId)
            ->first();
        if ($existItem) {
            $existItem->qty += $qty;
            $existItem->subtotal = $existItem->qty * $product->price;
            return $existItem->save();
        }

        $item = $this->cart->create([
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
        return $item;
    }
    // update item
    function updateCartDb()
    {
        $id = request()->input('rowId');
        $qty = request()->input('qty');

        $this->getCartById($id)->update(
            [
                'rowId' => $id,
                'qty' => $qty,
                'subtotal' => $qty * $this->getCartById($id)->price
            ]
        );
        $total = '$' . $this->queryCartbyUser()->sum('subtotal');
        $cartCount = $this->queryCartbyUser()->sum('qty');
        // $item['$total'] = $total;
        // $item['$cartCount'] = $cartCount;
        $item = $this->getCartById($id);
        if (!$item) {
            return null;
        }
        return $item;
    }
    // delete item
    function deleteCartDb($rowId)
    {
        $cart = $this->getCartById($rowId);
        if (!$cart) {
            return null;
        }
        $cart->delete();
        return $cart;
    }

}

?>