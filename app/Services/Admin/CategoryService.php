<?php
namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryService
{



    function getCategories()
    {
        $categories = Category::orderBy('name', 'asc')->paginate(10);
        return $categories;
    }

    function getCategorieByStatus()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        return $categories;
    }

    function create($data)
    {
        Category::create($data);
        return true;
    }

    function find($id)
    {
        $cate = Category::find($id);
        if (!$cate) {
            throw new \Exception('Not found Category ');
        }
        return $cate;
    }

    function edit($id)
    {
        $cate = $this->find($id);
        return $cate;
    }

    function update($id, $data)
    {
        $cate = $this->find($id);

        $cate->update($data);
        return $cate;
    }

    function delete($id)
    {
        $cate = $this->find($id);
        return $cate->delete();
    }


    function validateStore($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|unique:categories,name|max:255',
            'slug' => 'required|unique:categories,slug|max:255',
            'status' => 'required|in:1,2',
        ]);
        return $validator;
    }
    function validateUpdate($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:70|unique:categories,name,' . $id,
            'slug' => 'required|max:255|unique:categories,slug,' . $id,
            'status' => 'required|in:1,2',
        ]);

        return $validator;
    }


}


?>