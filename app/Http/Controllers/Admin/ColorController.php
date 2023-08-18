<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Admin\ColorService;
use Illuminate\Support\Str;
class ColorController extends Controller
{
    protected $colorService;
    function __construct(ColorService $colorService)
    {
        $this->colorService = $colorService;
    }
    public function index()
    {
        $colors = $this->colorService->getColors();
        return view('admin.color.add', compact('colors'));
    }
    function create()
    {
        return abort(404);
    }
    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required|unique:colors,name|max:255',
                'slug' => 'required|unique:colors,name|max:255',
                'status' => 'required|in:1,2',
            ]);
            if ($validator->fails()) {
                throw new \Exception('Color created failed');
            }
            $slug = str::slug($request->name);
            $data['slug'] = $slug;
            $this->colorService->store($data);
            return redirect()->back()->with('success', 'Color created successfully ');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }

    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        try {
            $color = $this->colorService->edit($id);
            return view('admin/color.show', compact('color'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required|max:70|unique:colors,name,' . $this->colorService->edit($id)->id,
                'status' => 'required|in:1,2',
            ]);

            if ($validator->fails()) {
                throw new \Exception('color update  failed');
            }
            $slug = str::slug($request->name);
            $data['slug'] = $slug;
            $update = $this->colorService->update($id, $data);
            $message = 'Update color successfully! ' . "<br> <b> " . $update->name . "</b>";
            return redirect(route('admin.colors.index'))->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }


    public function destroy($id)
    {
        try {
            $delete = $this->colorService->delete($id);
            $mess = 'Delete Color Success';
            return redirect()->back()->with('success', $mess);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}