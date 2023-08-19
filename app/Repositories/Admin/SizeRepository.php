<?php
namespace App\Repositories\Admin;
use App\Models\size;

class SizeRepository
{
    protected $size;
    function __construct(size $size)
    {
        $this->size = $size;
    }
    function getAll()
    {
        $sizes = $this->size->oldest('name')->paginate(10);
        return $sizes;
    }

    function getsizeByStatus()
    {
        $sizes = $this->size->where('status', 1)->oldest('name')->get();
        return $sizes;
    }

    // store
    function create($data)
    {
        return $this->size->create($data);
    }
    // find by id
    function find($id)
    {
        $size = $this->size::find($id);
        if (!$size) {
            return null;
        }
        return $size;
    }
    // update
    function update($id, $data)
    {
        $size = $this->find($id);
        $size->update($data);
        return $size;
    }
    function delete($id)
    {
        $size = Size::find($id);
        return $size->delete();
    }


}

?>