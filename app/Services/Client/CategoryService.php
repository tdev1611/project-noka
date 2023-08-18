<?php
namespace App\Services\Client;

use App\Repositories\Client\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;
    function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    function getCategories()
    {
        $categories = $this->categoryRepository->getAll();
        return $categories;
    }
    // get by slug
    function find($slug)
    {
        $category = $this->categoryRepository->getBySlug($slug);
        if ($category === null) {
            throw new \Exception('Category not found');
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