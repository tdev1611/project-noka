<?php
namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\CategoryRepository;

class CategoryService
{
    protected $categoryRepository;
    function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    // get all categories
    function getCategories()
    {
        $categories = $this->categoryRepository->getAll();
        return $categories;
    }
    // status =1 
    function getCategorieByStatus()
    {
        return $categories = $this->categoryRepository->getCategorieByStatus();
    }

    // store
    function validateStore($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|unique:categories,name|max:255',
            'slug' => 'required|unique:categories,slug|max:255',
            'status' => 'required|in:1,2',
        ]);
        return $validator;
    }
    function create($data)
    {
        $validator = $this->validateStore($data);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $this->categoryRepository->create($data);
    }

    function find($id)
    {
        $cate = $this->categoryRepository->find($id);
        if ($cate === null) {
            throw new \Exception('Not found Category');
        }
        return $cate;
    }
    // show
    function edit($id)
    {
        $cate = $this->find($id);
        return $cate;
    }

    // update
    function validateUpdate($id, $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:70|unique:categories,name,' . $id,
            'slug' => 'required|max:255|unique:categories,slug,' . $id,
            'status' => 'required|in:1,2',
        ]);
        return $validator;
    }

    function update($id, $data)
    {
        $validator = $this->validateUpdate($id, $data);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $cate = $this->categoryRepository->update($id, $data);
    }

    function delete($id)
    {
        $cate = $this->find($id);
        return $cate->delete();
    }


}


?>