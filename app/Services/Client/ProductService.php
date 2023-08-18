<?php
namespace App\Services\Client;

use App\Models\Product;


class ProductService
{
    function getProducts()
    {
        // $products = Product::where('status', 1)->orderBy('name', 'ASC')->paginate(10);
        // return $products;
        $products = Product::where('status', 1)->orderBy('name', 'ASC')->paginate(8);
        $search = request()->search;
        if ($search) {
            $products = Product::where('status', 1)->where('name', 'like', '%' . $search . '%')
                ->orderByDesc('created_at')->paginate(10);
        }
        return $products;
    }
    function find($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if (!$product) {
            throw new \Exception('Product not found');
        }
        return $product;
    }

    function getCategory($slug)
    {
        $product = $this->find($slug);
        return $product->category;
    }

    function getColors($slug)
    {
        $product = $this->find($slug);
        return $product->colors;
    }
    function getSizes($slug)
    {
        $product = $this->find($slug);
        return $product->sizes;
    }
}


?>