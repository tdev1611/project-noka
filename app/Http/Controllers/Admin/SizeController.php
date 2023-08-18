<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Admin\SizeService;
use Illuminate\Support\Str;

class sizeController extends Controller
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
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required|unique:sizes,name|max:255',
                'status' => 'required|in:1,2',
            ]);
            if ($validator->fails()) {
                throw new \Exception('Size created failed');
            }
            $slug = str::slug($request->name);
            $data['slug'] = $slug;
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
        //
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
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required|max:70|unique:sizes,name,' . $this->sizeService->find($id)->id,
                'status' => 'required|in:1,2',
            ]);
            if ($validator->fails()) {
                throw new \Exception('size update  failed');
            }
            $slug = str::slug($request->name);
            $data['slug'] = $slug;
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