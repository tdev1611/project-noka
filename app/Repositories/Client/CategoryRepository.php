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

}

?>