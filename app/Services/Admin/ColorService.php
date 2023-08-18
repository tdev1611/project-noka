<?php
namespace App\Services\Admin;

use App\Models\Color;

class ColorService
{
    function getColorByStatus()
    {
        return Color::where('status', 1)->orderBy('name', 'asc')->get();
    }
    function getColors()
    {
        $categories = Color::orderBy('name', 'asc')->paginate(10);
        return $categories;
    }

    function store($data)
    {
        Color::create($data);
        return true;
    }
    function find($id)
    {
        $cate = Color::find($id);

        if (!$cate) {
            throw new \Exception('Not found Color');
        }
        return $cate;
    }

    function edit($id)
    {
        $cate = $this->find($id);
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