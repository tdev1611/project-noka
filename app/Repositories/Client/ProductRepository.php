<?php
namespace App\Repositories\Client;

use App\Models\Product;

class ProductRepository
{
    protected $product;
    function __construct(Product $product)
    {
        $this->product = $product;
    }

    // status = 1 
    function getAllProduct()
    {
        $products = $this->product->where('status', 1)->oldest('name')->paginate(8);
        return $products;
    }

    function searchProduct($search)
    {
        return $this->product->where('status', 1)->where('name', 'like', '%' . $search . '%')
            ->latest()->paginate(10);
    }
    // find product by slug
    function findProductBySlug($slug)
    {
        return $this->product::where('slug', $slug)->first();
    }



}

?>