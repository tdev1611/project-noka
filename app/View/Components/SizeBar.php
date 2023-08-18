<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\Client\CategoryService;
use App\Services\Client\ColorService;
use App\Services\Client\SizeService;

class SizeBar extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $categoryService, $sizeService, $colorService;
    function __construct(CategoryService $categoryService, ColorService $colorService, SizeService $sizeService)
    {
        $this->categoryService = $categoryService;
        $this->colorService = $colorService;
        $this->sizeService = $sizeService;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $categories = $this->categoryService->getCategories();
        $colors = $this->colorService->getColors();
        $sizes = $this->sizeService->getSizes();
        return view('components.size-bar', compact('categories', 'sizes', 'colors', ));
    }
}