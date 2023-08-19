<?php
namespace App\Services\Client;

use App\Models\Size;
use App\Repositories\Client\SizeRepository;

class SizeService
{
    protected $sizeRepository;
    function __construct(SizeRepository $sizeRepository)
    {
        $this->sizeRepository = $sizeRepository;
    }

    function getSizes()
    {
        return $this->sizeRepository->getAll();
    }

    // get by slug
    function find($slug)
    {
        $size = $this->sizeRepository->getBySlug($slug);
        if ($size === null) {
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