<?php
namespace App\Services\Client;

use App\Models\Size;

class SizeService
{


    function getSizes()
    {
        return Size::where('status', 1)->orderBy('name', 'asc')->get();
    }

    function find($slug)
    {
        $size = Size::where('slug', $slug)->first();
        if (!$size) {
            throw new \Exception('Not found Size');
        }
        return $size;
    }
    function getProduct($slug)
    {
        $size = $this->find($slug);
        $products = $size->products()->paginate(8);
        return $products;
    }

}


?>