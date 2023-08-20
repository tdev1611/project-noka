<?php
namespace App\Services\Client;
use App\Repositories\Client\ProductRepository;

class ProductService
{
    protected $productRepository;
    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    function getProducts()
    {
        $products = $this->productRepository->getAllProduct();
        $search = request()->search;
        if ($search) {
            $products = $this->productRepository->searchProduct($search);
        }
        return $products;
    }
    function find($slug)
    {
        $product = $this->productRepository->findProductBySlug($slug);
        if (!$product) {
            throw new \Exception('Product not found');
        }
        return $product;
    }
    // products by cate
    function getCategory($slug)
    {
        $product = $this->find($slug);
        return $product->category;
    }

    // products by color
    function getColors($slug)
    {
        $product = $this->find($slug);
        return $product->colors;
    }
    // products by size
    function getSizes($slug)
    {
        $product = $this->find($slug);
        return $product->sizes;
    }
}


?>