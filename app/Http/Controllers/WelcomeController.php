<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Client\CategoryService;
use App\Services\Client\ProductService;
use App\Services\Client\ColorService;
use App\Services\Client\SizeService;

class WelcomeController extends Controller
{

    protected $productService, $categoryService, $sizeService, $colorService;
    function __construct(ProductService $productService, CategoryService $categoryService, ColorService $colorService, SizeService $sizeService)
    {
        $this->categoryService = $categoryService;
        $this->colorService = $colorService;
        $this->sizeService = $sizeService;
        $this->productService = $productService;
    }
    function index()
    {
       
        $products = $this->productService->getProducts();
        return view('welcome', compact('products'));
    }



    function searchClient()
    {
        $categories = $this->categoryService->getCategories();
        $colors = $this->colorService->getColors();
        $sizes = $this->sizeService->getSizes();
        $products = $this->productService->getProducts();
        $search = request()->search;
        return view('search', compact('categories', 'sizes', 'colors', 'products','search'));
    }
}