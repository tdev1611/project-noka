<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\CategoryService;
use App\Services\Admin\ProductService;
use App\Services\Admin\ColorService;
use App\Services\Admin\SizeService;


class ProductController extends Controller
{
    protected $productService, $categoryService,
    $sizeService, $colorService;

    function __construct(
        ProductService $productService, CategoryService $categoryService, SizeService $sizeService, ColorService $colorService
    ) {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
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
            $request->hasFile('list_image') ? $data['list_image'] = $this->productService
                ->handleUpLoadListImages($request->file('list_image'), $request->slug) : null;
            // handle uploadImg
            $request->hasFile('image') ? $data['image'] = $this->productService
                ->handleUploadedImage($request->file('image'), $request->slug) : null;
            // create
            $product = $this->productService->store($data);

            $product->colors()->attach($request->colors);
            $product->sizes()->attach($request->sizes);
            return redirect()->back()->with('success', 'Product created successfully ');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
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

            // update list_images
            $request->hasFile('list_image') ? $data['list_image'] = $this->productService
                ->updateListImages($id, $request->list_image, $request->slug) : null;
            // update image
            $request->hasFile('image') ? $data['image'] = $this->productService
                ->UpdateImage($id, $request->file('image'), $request->slug) : '';
            // update
            $product = $this->productService->update($id, $data);

            $product->colors()->sync($request->colors);
            $product->sizes()->sync($request->sizes);
            $message = 'Update product successfully! ' . "<br> <b> " . $product->name . "</b>";
            return redirect(route('admin.products.index'))->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }
    public function show($id)
    {
        return abort(404);
    }
  
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

    // optiona method : delete - restore - delete Force
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