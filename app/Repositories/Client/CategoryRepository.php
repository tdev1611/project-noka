<?php
namespace App\Repositories\Client;

use App\Models\Category;

class CategoryRepository
{
    protected $category;
    function __construct(Category $category)
    {
        $this->category = $category;
    }
    function getAll()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'ASC')->paginate(8);
        return $categories;
    }

    // get by slug
    function getBySlug($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return null;
        }
        return $category;
    }


}

?>