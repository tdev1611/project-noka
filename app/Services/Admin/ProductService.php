<?php
namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\ProductRepository;

class ProductService
{
    protected $productRepository;
    function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    // get data
    function getProduct()
    {
        $products = $this->productRepository->getProducts();
        $status = request()->status;
        $search = request()->search;
        if ($status == 'disabled') {
            $products = $this->productRepository->searchProductsTrashed($search);
        } else {
            if ($search) {
                $products = $this->productRepository->searchProducts($search);
            }
        }
        return $products;
    }
    function getRank()
    {
        $products = $this->productRepository->getProducts();
        return $products->firstItem();
    }

    function productCount()
    {
        return $this->productRepository->productCount();
    }

    function getTrashed()
    {
        $products = $this->productRepository->getTrashed();
        return $products;
    }

    // ----------------------------------------------------------------
    function handleUploadedImage($image, $slug)
    {
        $filename = $slug . '-' . time() . '.' . $image->getClientOriginalExtension();
        $path = $image->move('public/uploads/products', $filename);
        return "public/uploads/products/" . $filename;
    }
    // listImgs 
    function handleUpLoadListImages($images, $slug)
    {
        $list_images = [];
        foreach ($images as $file) {
            $filename = uniqid() . '-' . $slug . '.' . strtolower($file->getClientOriginalExtension());
            $path = $file->move('public/uploads/products/list_image', $filename);
            $list_images[] = "public/uploads/products/list_image/" . $filename;
        }
        return json_encode($list_images);
    }
    // updateImage
    function UpdateImage($id, $newImage, $slug)
    {
        if (!empty($newImage)) {
            $img_old = $this->find($id)->image;
            unlink($img_old);
        }
        $filename = $slug . '-' . time() . '.' . strtolower($newImage->getClientOriginalExtension());
        $path = $newImage->move('public/uploads/products', $filename);
        return $img = "public/uploads/products/" . $filename;

    }
    // updateListImages
    function updateListImages($id, $newListImages, $slug)
    {
        $list_images = [];
        if (!empty($newListImages)) {
            $list_imgOld = json_decode($this->find($id)->list_image, true);
            foreach ($list_imgOld as $imgOld) {
                if (File::exists($imgOld)) {
                    File::delete($imgOld);
                }
            }
        }
        foreach ($newListImages as $image) {
            $filename = uniqid() . '-' . $slug . '.' . strtolower($image->getClientOriginalExtension());
            $path = $image->move('public/uploads/products/list_image', $filename);
            $list_images[] = "public/uploads/products/list_image/" . $filename;
        }
        return json_encode($list_images);
    }
    // CRUD
    function store($data)
    {
        return $this->productRepository->create($data);
    }

    function find($id)
    {
        $product = $this->productRepository->findById($id);
        if (!$product) {
            throw new \Exception('Not found Product ');
        }
        return $product;
    }

    function update($id, $data)
    {
        return $this->productRepository->update($id, $data);

    }
    function delete($id)
    {
        return $this->productRepository->delete($id);
    }

    function restore($ids)
    {
        if (!$ids) {
            throw new \Exception('Not found product to restore');
        }
        return $this->productRepository->restore($ids);

    }

    function destroy($ids)
    {
        if (!$ids) {
            throw new \Exception('Not Found Product');
        }
        return $this->productRepository->destroy($ids);
    }
    function deleteForce($id)
    {
        $product = $this->productRepository->getProductTrashedById($id);
        if (!$product) {
            throw new \Exception('Not found product to delete force');
        }
        unlink($product->image);
        $list_imgs = json_decode($product->list_image, true);
        foreach ($list_imgs as $img) {
            if (File::exists($img)) {
                File::delete($img);
            }
        }
        return $product->forceDelete();
    }
    // getProductTrashed
    function deleteForceListCheck($ids)
    {
        if (!$ids) {
            throw new \Exception('Not found product to delete force');
        }
        $productsTrash = $this->productRepository->getProductTrashed($ids);
        $products = $productsTrash->get();
        foreach ($products as $product) {
            unlink($product->image);
            $list_imgs = json_decode($product->list_image, true);
            foreach ($list_imgs as $img) {
                if (File::exists($img)) {
                    File::delete($img);
                }
            }
        }
        return $productsTrash->forceDelete();
    }

    // validation
    function validateStore($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|unique:products,name|max:70',
            'slug' => 'required|unique:products,slug|max:70',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'required|image|',
            'list_image' => 'required|array',
            'list_images.*' => 'image|mimes:jpeg,png',
            'desc' => 'required',
            'detail' => 'required',
            'status' => 'required|in:1,2',
            'colors' => 'required|exists:colors,id',
            'sizes' => 'required|exists:sizes,id',
        ]);
        return $validator;
    }

    function validateUpadte($data, $id)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:70|unique:products,name,' . $id,
            'slug' => 'required|max:70|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'image' => 'image|required_if:is_updating_file,true|mimes:jpeg,png,pdf',
            'list_image' => 'array',
            'list_images.*' => 'image|mimes:jpeg,png|max:2048',
            'desc' => 'required',
            'detail' => 'required',
            'status' => 'required|in:1,2',
            'colors' => 'required|exists:colors,id',
            'sizes' => 'required|exists:sizes,id',
        ]);
        return $validator;
    }


}


?>