<?php
namespace App\Services\Admin;

use App\Models\Size;

class SizeService
{


    function getSizeByStatus()
    {
        return Size::where('status', 1)->orderBy('name', 'asc')->get();
    }
    function getSizes()
    {
        $categories = Size::orderBy('name', 'asc')->paginate(10);
        return $categories;
    }

    function store($data)
    {
        Size::create($data);
        return true;
    }

    function find($id)
    {
        $cate = Size::find($id);

        if (!$cate) {
            throw new \Exception('Not found Size');
        }
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

}


?>