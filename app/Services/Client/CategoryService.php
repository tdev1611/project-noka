<?php
namespace App\Services\Client;

use App\Models\Category;


class CategoryService
{
    function getCategories()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->paginate(8);
        return $categories;
    }
    function find($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            throw new \Exception('Not Found category');
        }
        return $category;
    }
    function getProduct($slug)
    {
        $category = $this->find($slug);
        $products = $category->products()->paginate(8);
        return $products;
    }

}


?>