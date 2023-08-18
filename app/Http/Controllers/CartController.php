<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Gloudemans\Shoppingcart\Facades\Cart as CartSession;
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
    function add()
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
    function updateAjax()
    {
        try {
            if (!Auth::user()) {
                $item = $this->cartService->updateCartSessions();
                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully ' . $item->name,
                    'subtotal' => '$' . $item->subtotal,
                    'total' => '$' . CartSession::total(),
                    'cartCount' => CartSession::count(),
                    'qty' => $item->qty,
                ]);

            }
            // -----
            $item = $this->cartService->updateCartDb();
            $total = '$' . Cart::where('user_id', Auth::user()->id)->sum('subtotal');
            $cartCount = Cart::where('user_id', Auth::user()->id)->sum('qty');
            return response()->json([
                'status' => true,
                'message' => 'Updated successfully ' . $item->name,
                'subtotal' => '$' . $item->subtotal,
                'total' => $total,
                'cartCount' => $cartCount,
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
                $cart = CartSession::content()->where('rowId', $rowId);
                if ($cart->isNotEmpty()) {
                    CartSession::remove($rowId);
                }
                return redirect()->back()->with('success', 'Remove Product Success to Cart');
            }
            $this->cartService->deleteCartDb($rowId);
            return redirect()->back()->with('success', 'Remove Product Success to Cart');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }





}