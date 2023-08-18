<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{


    function showLoginForm()
    {
        if (auth('admin')->user()) {
            return redirect()->back()->with('success', 'You logged in successfully');
        }
        return view('auth.admin.login');
    }
    function login(Request $request)
    {
        try {
            $input = $request->only('email', 'password');
            $validator = Validator::make(
                $input,
                [
                    'email' => 'required|email',
                    'password' => 'required',
                ],
            );
            if ($validator->fails()) { {
                    throw new \Exception('Đăng nhập không thành công. ');
                }
            }
            if (Auth::guard('admin')->attempt($input)) {
                return redirect()->route('admin.products.index')->with('success', 'Đăng nhập thành công');
            } else {
                return redirect()->back()->with('error', 'Đăng nhập không thành công. Vui lòng kiểm tra lại email và mật khẩu.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }


    function logout()
    {
        Auth::guard('admin')->logout();
        return redirect(route('admin.login.form'));
    }



}