<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Admin\ColorService;


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
        $data = $request->all();
        try {
            $this->colorService->store($data);
            return back()->with('success', 'Color created successfully ');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        return abort(404);
    }


    public function edit($id)
    {
        try {
            $color = $this->colorService->edit($id);
            return view('admin/color.show', compact('color'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        try {
            $update = $this->colorService->update($id, $data);
            $message = 'Update color successfully! ' . "<br> <b> " . $update->name . "</b>";
            return redirect(route('admin.colors.index'))->with('success', $message);
        } catch (\Exception $e) {
            return back()
                ->with('error', $e->getMessage())->withInput();
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