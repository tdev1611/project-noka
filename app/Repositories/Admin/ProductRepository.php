<?php
namespace App\Repositories\Admin;

use App\Models\Product;

class ProductRepository
{
    protected $product;
    function __construct(Product $product)
    {
        $this->product = $product;
    }


    // get products
    function getProducts()
    {
        $products = $this->product->oldest('name')->with('colors', 'sizes')
            ->paginate(10);

        return $products;
    }

    // search products 
    function searchProducts($search)
    {
        $products = $this->product->where('name', 'like', '%' . $search . '%')
            ->orWhere('price', 'like', '%' . $search . '%')
            ->with('colors', 'sizes')
            ->latest()
            ->paginate(10);
        return $products;
    }

    // search products onlyTrash
    function searchProductsTrashed($search)
    {
        $products = $this->product->onlyTrashed()
            ->with('colors', 'sizes')
            ->where('name', 'like', '%' . $search . '%')
            ->latest()->paginate(10);
        return $products;
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


    function findById($id)
    {
        $product = $this->product->find($id);
        if (!$product) {
            throw new \Exception('Not found Product ');
        }
        return $product;
    }
    // CRUD
    function create($data)
    {
        return $this->product->create($data);
    }
    function update($id, $data)
    {
        $product = $this->findById($id);
        $product->update($data);
        return $product;
    }
    function delete($id)
    {
        $product = $this->findById($id);
        return $product->delete();
    }

    # trashed
    // products trashed
    function getProductTrashed($ids)
    {
        return $this->product->onlyTrashed()->whereIn('id', $ids);
    }
    // product in trashed by Id 
    function getProductTrashedById($id)
    {
        $product = $this->product->onlyTrashed()->find($id);
        return $product;
    }

    // restore
    function restore($ids)
    {
        $product = $this->getProductTrashed($ids);
        return $product->restore();
    }
    function destroy($id)
    {
        return $this->product->destroy($id);
    }

}

?>