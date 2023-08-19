<?php
namespace App\Services\Client;
use App\Repositories\Client\ColorRepository;

class ColorService
{
    protected $colorRepository;
    function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }
    function getColors()
    {
        return $this->colorRepository->getAll();
    }

    // get by slug
    function find($slug)
    {
        $color = $this->colorRepository->getBySlug($slug);
        if ($color === null) {
            throw new \Exception('Color not found');
        }
        return $color;
    }
   
    function getProduct($slug)
    {
        $color = $this->find($slug);
        $products = $color->products()->paginate(8);
        return $products;
    }

}


?>