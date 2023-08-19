<?php
namespace App\Repositories\Client;

use App\Models\size;
class SizeRepository
{
    protected $size;
    function __construct(Size $size)
    {
        $this->size = $size;
    }
    // bystatus =1 
    function getAll()
    {
        return $this->size->where('status', 1)->oldest()->paginate(8);

    }
    // get by slug
    function getBySlug($slug)
    {
        $size = $this->size->where('slug', $slug)->first();
        if (!$size) {
            return null;
        }
        return $size;
    }
}

?>