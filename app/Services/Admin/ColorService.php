<?php
namespace App\Services\Admin;

use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\ColorRepository;
use Illuminate\Support\Str;

class ColorService
{
    protected $colorRepository;
    function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }
    function getColorByStatus()
    {
        return $this->colorRepository->getColorByStatus();
    }
    function getColors()
    {
        return $colors = $this->colorRepository->getAll();
    }


    // store
    function validateStore($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|unique:colors,name|max:255',
            'slug' => 'required|unique:colors,slug|max:255',
            'status' => 'required|in:1,2',
        ]);
        return $validator;
    }
    function store($data)
    {
        $slug = str::slug(request()->name);
        $data['slug'] = $slug;
        $validator = $this->validateStore($data);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $this->colorRepository->create($data);
    }
    function find($id)
    {
        $color = $this->colorRepository->find($id);
        if ($color === null) {
            throw new \Exception('Not found Color');
        }
        return $color;
    }
    // show
    function edit($id)
    {
        $color = $this->find($id);
        return $color;
    }
    // update
    function validateUpdate($id, $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:70|unique:colors,name,' . $id,
            'slug' => 'required|max:255|unique:colors,slug,' . $id,
            'status' => 'required|in:1,2',
        ]);
        return $validator;
    }
    function update($id, $data)
    {
        $slug = str::slug(request()->name);
        $data['slug'] = $slug;
        $validator = $this->validateUpdate($id, $data);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        return $this->colorRepository->update($id, $data);
    }

    function delete($id)
    {
        $color = $this->find($id);
        return $color->delete();
    }

}


?>