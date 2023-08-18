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
        $products = Product::orderBy('name', 'ASC')->paginate(10);
        $status = request()->status;
        $search = request()->search;
        if ($status == 'disabled') {
            $products = Product::onlyTrashed()->where('name', 'like', '%' . $search . '%')
                ->orderByDesc('created_at')->paginate(10);
        } else {
            if ($search) {
                $products = Product::where('name', 'like', '%' . $search . '%')
                    ->orWhere('price', 'like', '%' . $search . '%')
                    ->paginate(10);
            }
        }
        return $products;
    }
    function getRank()
    {
        $products = Product::orderBy('name', 'ASC')->paginate(10);
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
        return $product->forceDelete();
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