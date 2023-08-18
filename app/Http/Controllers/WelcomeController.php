<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Client\ProductService;


class WelcomeController extends Controller
{

    protected $productService;
    function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    function index()
    {
        $products = $this->productService->getProducts();
        return view('welcome', compact('products'));
    }



    function searchClient()
    {
        $search = request()->search;
        $products = $this->productService->getProducts();
        return view('search', compact('products', 'search'));
    }
}