<?php



namespace App\Repositories\Admin;

use App\Models\Color;

class ColorRepository
{
    protected $color;
    function __construct(Color $color)
    {
        $this->color = $color;
    }

    function getAll()
    {
        $colors = $this->color->oldest('name')->paginate(10);
        return $colors;
    }

    function getColorByStatus()
    {
        $colors = $this->color->where('status', 1)->oldest('name')->get();
        return $colors;
    }

    // store
    function create($data)
    {
        return $this->color->create($data);
    }
    // find by id
    function find($id)
    {
        $color = $this->color::find($id);
        if (!$color) {
            return null;
        }
        return $color;
    }
    // update
    function update($id, $data)
    {
        $color = $this->find($id);
        $color->update($data);
        return $color;
    }


}

?>