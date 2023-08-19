<?php
namespace App\Services\Admin;

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductService
{
    // get data
    function getProduct()
    {
        $products = Product::oldest('name')
            ->with('colors', 'sizes')
            ->paginate(10);
        $status = request()->status;
        $search = request()->search;
        if ($status == 'disabled') {
            $products = Product::onlyTrashed()
                ->with('colors', 'sizes')
                ->where('name', 'like', '%' . $search . '%')
                ->latest()->paginate(10);
        } else {
            if ($search) {
                $products = Product::where('name', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%')
                    ->with('colors', 'sizes')
                    ->latest()
                    ->paginate(10);
            }
        }
        return $products;
    }
    function getRank()
    {
        $products = Product::oldest('name')->paginate(10);
        return $products->firstItem();
    }

    function productCount()
    {
        return Product::count();
    }

    function getTrashed()
    {
        $products = Product::onlyTrashed()->get();
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
        return Product::create($data);

    }

    function find($id)
    {
        $product = Product::find($id);
        if (!$product) {
            throw new \Exception('Not found Product ');
        }
        return $product;
    }

    function update($id, $data)
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }
    function delete($id)
    {
        $product = $this->find($id);
        return $product->delete();
    }

    function restore($ids)
    {
        if (!$ids) {
            throw new \Exception('Not found product to restore');
        }
        $product = Product::onlyTrashed()->whereIn('id', $ids);
        return $product->restore();
    }

    function destroy($ids)
    {
        if (!$ids) {
            throw new \Exception('Not Found Product');
        }
        return Product::destroy($ids);
    }
    function deleteForce($id)
    {
        $product = Product::onlyTrashed()->find($id);
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

    function deleteForceListCheck($ids)
    {
        if (!$ids) {
            throw new \Exception('Not found product to delete force');
        }
        $products = Product::onlyTrashed()->whereIn('id', $ids)->get();
        foreach ($products as $product) {
            unlink($product->image);
            $list_imgs = json_decode($product->list_image, true);
            foreach ($list_imgs as $img) {
                if (File::exists($img)) {
                    File::delete($img);
                }
            }
        }
        return Product::onlyTrashed()->whereIn('id', $ids)->forceDelete();
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