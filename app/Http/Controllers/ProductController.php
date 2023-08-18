<?php

namespace App\Http\Controllers;

use App\Services\Client\ProductService;
use App\Services\Client\CategoryService;

use App\Services\Client\ColorService;
use App\Services\Client\SizeService;



class ProductController extends Controller
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
        abort(404);
    }
    function detail($slug)
    {
        try {
            $productService = new ProductService();
            $product = $productService->find($slug);
            $category = $productService->getCategory($slug);
            $colors = $productService->getColors($slug);
            $sizes = $productService->getSizes($slug);
            return view('product.detail', compact('product', 'category', 'colors', 'sizes'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }
    function productByCategory($slug)
    {
        $nameCate = $this->categoryService->find($slug)->name;
        $products = $this->categoryService->getProduct($slug);
        return view('product.byCategory', compact('products', 'nameCate'));
    }


    function productByColor($slug)
    {
        $color = $this->colorService->find($slug);
        $nameColor = $color->name;
        $products = $this->colorService->getProduct($slug);
        return view('product.byColor', compact('products', 'nameColor'));
    }

    function productBySize($slug)
    {
        $size = $this->sizeService->find($slug);
        $nameSize = $size->name;
        $products = $this->sizeService->getProduct($slug);
        return view('product.bySize', compact('products', 'nameSize'));

    }





}