<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\SizeService;


class SizeController extends Controller
{

    protected $sizeService;
    function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }
    public function index()
    {
        $sizes = $this->sizeService->getSizes();

        return view('admin.size.add', compact('sizes'));
    }


    public function store(Request $request)
    {
        $data = $request->all();
        try {
            $validator = $this->sizeService->validateStore($data);
            $this->sizeService->store($data);
            return redirect()->back()->with('success', 'Size created successfully ');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }
    function create()
    {
        return abort(404);
    }
    public function show($id)
    {
        return abort(404);
    }

    public function edit($id)
    {
        try {
            $size = $this->sizeService->find($id);
            return view('admin/size.show', compact('size'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $validator = $this->sizeService->validateStore($data);
            $update = $this->sizeService->update($id, $data);
            $message = 'Update size successfully! ' . "<br> <b> " . $update->name . "</b>";
            return redirect(route('admin.sizes.index'))->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $delete = $this->sizeService->delete($id);
            $mess = 'Delete size Success';
            return redirect()->back()->with('success', $mess);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}