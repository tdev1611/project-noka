<?php
namespace App\Repositories\Admin;

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
        $categories = $this->category->oldest('name')->paginate(10);
        return $categories;
    }

    function getCategorieByStatus()
    {
        $categories = $this->category->where('status', 1)->oldest('name')->get();
        return $categories;
    }

    // store
    function create($data)
    {
        return $this->category->create($data);
    }
    // find by id
    function find($id)
    {
        $cate = $this->category::find($id);
        if (!$cate) {
            return null;
        }
        return $cate;
    }
    // update
    function update($id, $data)
    {
        $cate = $this->find($id);
        $cate->update($data);
        return $cate;
    }


}

?>