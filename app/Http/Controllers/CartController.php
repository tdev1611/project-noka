<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Gloudemans\Shoppingcart\Facades\Cart as SessionCart;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Services\Client\CartService;

class CartController extends Controller
{
    protected $cartService;
    function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    function index()
    {
        if (!Auth::user()) {
            $cart = $this->cartService->getSessionCart();
            return view('cart.index', compact('cart'));
        }
        $cart = $this->cartService->getCartByUser();
        $total = $cart->sum('subtotal');
        return view('cart.index', compact('cart', 'total'));
    }
    function add(Request $request)
    {
        try {
            if (!Auth::user()) {
                $this->cartService->addSessionCart();
                $cartCount = $this->cartService->CountSessionCart();
                return response()->json([
                    'status' => 'success',
                    'messages' => 'Added product successfully to cart',
                    'cartCount' => $cartCount,
                ]);
            }
            // ----------------------------------------------------------------
            $this->cartService->addCartDb();
            $cartCount = $this->cartService->CartCountDb();
            return response()->json([
                'status' => 'success',
                'messages' => 'Added product successfully to cart',
                'cartCount' => $cartCount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'messages' => $e->getMessage(),
            ]);
        }
    }
    function buyNow(Request $request, $id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                throw new \Exception('Product not found to buy');
            }
            $slug = $product->slug;
            $color = $request->colors;
            $size = $request->sizes;
            $qty = $request->input('num_order');
            Cart::add([
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
            return redirect()->back()->with('success', 'buy Product Success to Cart');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    function updateAjax(Request $request)
    {
        try {
            if (!Auth::user()) {
                $id = $request->input('rowId');
                $qty = $request->input('qty');
                SessionCart::update($id, $qty);
                $item = SessionCart::get($id);
                $subtotal = '$' . $item->subtotal;
                $total = '$' . SessionCart::total();
                $cartCount = SessionCart::count();
                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully ' . $item->name,
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'cartCount' => $cartCount,
                    'qty' => $qty,
                ]);
            }
            // -----
            $id = $request->input('rowId');
            $qty = $request->input('qty');
            Cart::where('rowId', $id)->update(
                [
                    'rowId' => $id,
                    'qty' => $qty,
                    'subtotal' => $qty * Cart::where('rowId', $id)->first()->price
                ]
            );
            // $item = Cart::get($id);
            $item = Cart::where('rowId', $id)->first();
            $subtotal = '$' . $item->subtotal;
            $total = '$' . Cart::where('user_id', Auth::user()->id)->sum('subtotal');
            $cartCount = Cart::where('user_id', Auth::user()->id)->sum('qty');
            ;
            return response()->json([
                'status' => true,
                'message' => 'Updated successfully ' . $item->name,
                'subtotal' => $subtotal,
                'total' => $total,
                'cartCount' => $cartCount,
                'qty' => $qty,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }
    function remove($rowId)
    {
        try {
            if (!Auth::user()) {
                $cart = SessionCart::content()->where('rowId', $rowId);
                if ($cart->isNotEmpty()) {
                    SessionCart::remove($rowId);
                }
                return redirect()->back()->with('success', 'Remove Product Success to Cart');
            }
            $cart = Cart::where('rowId', $rowId)->first();
            if (!$cart) {
                throw new \Exception('Product not found');
            }
            $cart->delete();
            return redirect()->back()->with('success', 'Remove Product Success to Cart');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }





}