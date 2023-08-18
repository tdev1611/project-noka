<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Admin\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;
    function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        $categories = $this->categoryService->getCategories();

        return view('admin/category.add', compact('categories'));
    }

    public function create()
    {
        return abort(404);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $this->categoryService->create($data);
            return back()->with('success', 'Category created successfully ');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }

    }
    public function show($id)
    {
        return abort(404);
        // return view('admin/category.show');
    }


    public function edit($id)
    {
        try {
            $category = $this->categoryService->edit($id);
            return view('admin/category.show', compact('category'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $update = $this->categoryService->update($id, $data);
            $message = 'Update category successfully! ' . "<br> <b> " . $update->name . "</b>";
            return redirect(route('admin.categories.index'))->with('success', $message);
        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage())->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            $this->categoryService->delete($id);
            $mess = 'Delete Category Success';
            return redirect()->back()->with('success', $mess);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}