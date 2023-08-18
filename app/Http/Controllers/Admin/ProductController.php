<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\CategoryService;
use App\Services\Admin\ProductService;
use App\Services\Admin\ColorService;
use App\Services\Admin\SizeService;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    protected $categoryService, $sizeService, $productService, $colorService;

    function __construct(ProductService $productService, CategoryService $categoryService, SizeService $sizeService, ColorService $colorService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->sizeService = $sizeService;
        $this->colorService = $colorService;
    }
    public function index(Request $request)
    {
        $products = $this->productService->getProduct();
        $rank = $this->productService->getRank();
        $trashed = $this->productService->getTrashed()->count();
        $countProducts = $this->productService->productCount();
        return view('admin.product.index', compact('products', 'rank', 'trashed', 'countProducts'));

    }
    public function create()
    {
        $categories = $this->categoryService->getCategorieByStatus();
        $sizes = $this->sizeService->getSizeByStatus();
        $colors = $this->colorService->getColorByStatus();
        return view('admin.product.add', compact('categories', 'sizes', 'colors'));
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $validator = $this->productService->validateStore($data);
            if ($validator->fails()) {
                throw new \Exception('Product created failed');
            }
            // list imgs 
            if ($request->hasFile('list_image')) {
                $list_images = [];
                $images = $request->file('list_image');
                foreach ($images as $file) {
                    $filename = uniqid() . '-' . $request->slug . '.' . strtolower($file->getClientOriginalExtension());
                    $path = $file->move('public/uploads/products/list_image', $filename);
                    $list_images[] = "public/uploads/products/list_image/" . $filename;
                }
                $data['list_image'] = json_encode($list_images);
              
            }
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = $request->slug . '-' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->move('public/uploads/products', $filename);
                $img = "public/uploads/products/" . $filename;
                $data['image'] = $img;
            }
            $product = $this->productService->store($data);
            $colors = $request->input('colors');
            $sizes = $request->input('sizes');
            $product->colors()->attach($colors);
            $product->sizes()->attach($sizes);
            return redirect()->back()->with('success', 'Product created successfully ');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        //
        try {
            $product = $this->productService->find($id);
            $categories = $this->categoryService->getCategorieByStatus();
            $sizes = $this->sizeService->getSizeByStatus();
            $colors = $this->colorService->getColorByStatus();
            $selectColors = $product->colors->pluck('id')->toArray();
            $selectSizes = $product->sizes->pluck('id')->toArray();
            return view('admin/product.show', compact('product', 'categories', 'colors', 'sizes', 'selectSizes', 'selectColors'));
        } catch (\Exception $e) {
            return redirect(route('admin.products.index'))->with('error', $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $validator = $this->productService->validateUpadte($data, $id);
            if ($validator->fails()) {
                throw new \Exception('Product update  failed!');
            }
            // list images
            if ($request->hasFile('list_image')) {
                $list_images = [];
                $images = $request->file('list_image');
                if (!empty($images)) {
                    $list_imgOld = json_decode($this->productService->find($id)->list_image, true);
                    foreach ($list_imgOld as $imgOld) {
                        if (File::exists($imgOld)) {
                            File::delete($imgOld);
                        } 
                    }
                }
                foreach ($images as $image) {
                    $filename = uniqid() . '-' . $request->slug . '.' . strtolower($image->getClientOriginalExtension());
                    $path = $image->move('public/uploads/products/list_image', $filename);
                    $list_images[] = "public/uploads/products/list_image/" . $filename;
                }
                $data['list_image'] = json_encode($list_images);
            }
            // image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if (!empty($image)) {
                    $img_old = $this->productService->find($id)->image;
                    unlink($img_old);
                }
                $filename = $request->slug . '-' . time() . '.' . strtolower($image->getClientOriginalExtension());
                $path = $image->move('public/uploads/products', $filename);
                $img = "public/uploads/products/" . $filename;
                $data['image'] = $img;
            }
            $product = $this->productService->update($id, $data);
            $message = 'Update product successfully! ' . "<br> <b> " . $product->name . "</b>";

            $colors = $request->input('colors');
            $sizes = $request->input('sizes');
            
            $product->colors()->sync($colors);
            $product->sizes()->sync($sizes);
            return redirect(route('admin.products.index'))->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }
    public function show($id)
    {
        return abort(404);
    }
    // public function destroy($id)
    // {
    //     try {
    //         $this->productService->delete($id);
    //         return redirect()->back()->with('success', 'Delete products successfully!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', $e->getMessage());
    //     }
    // }
    function delete($id)
    {
        try {
            $this->productService->delete($id);
            return redirect()->back()->with('success', 'Delete products successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function restore($id)
    {
        try {
            $this->productService->restore([$id]);
            return redirect()->back()->with('success', 'Restored Product Success ');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function deleteForce($id)
    {
        try {
            $this->productService->deleteForce($id);
            return redirect()->back()->with('success', 'Force deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());

        }
    }

    function action(Request $request)
    {

        try {
            $action = $request->action;
            $list_check = $request->list_check;
            if (empty($action)) {
                throw new \Exception('You must specify method');
            }
            if (empty($list_check)) {
                throw new \Exception('You must specify  record ');
            }
            $message = '';
            switch ($action) {
                case 'delete':
                    $this->productService->destroy($list_check);
                    $message = 'Delete successfully';
                    break;
                case 'restore':
                    $this->productService->restore($list_check);
                    $message = 'Restore successfully';
                    break;
                case 'forceDelelte':
                    $this->productService->deleteForceListCheck($list_check);
                    $message = 'Force delete  successfully';
            }
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }


    }



}