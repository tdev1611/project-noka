<?php
namespace App\Services\Admin;

use Illuminate\Support\Facades\Validator;
use App\Repositories\Admin\SizeRepository;
use Illuminate\Support\Str;

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
    function getSizeByStatus()
    {
        return $this->sizeRepository->getsizeByStatus();
    }

    // store
    function validateStore($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|unique:sizes,name|max:255',
            'slug' => 'required|unique:sizes,slug|max:255',
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
        return $this->sizeRepository->create($data);
    }
    function find($id)
    {
        $size = $this->sizeRepository->find($id);
        if ($size === null) {
            throw new \Exception('Not found Size');
        }
        return $size;
    }
    // show
    function edit($id)
    {
        $size = $this->find($id);
        return $size;
    }
    // update
    function validateUpdate($id, $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|max:70|unique:sizes,name,' . $id,
            'slug' => 'required|max:255|unique:sizes,slug,' . $id,
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
        return $this->sizeRepository->update($id, $data);
    }

    function delete($id)
    {
        return $color = $this->sizeRepository->delete($id);
    }

}


?>