<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserService
{
    function register($data)
    {
        return  User::create($data);
        
    }
    // request
    function formRequestAll()
    {
        $data = request()->all();
        return $data;
    }
    function formRequestLogin()
    {
        $data = request()->only('email', 'password');
        return $data;
    }
    // validateSteor
    function validateStore($data)
    {
        $validator = Validator::make(
            $data,
            [
                'name' => 'required|min:5',
                'email' => 'required|email|min:5|unique:users,email',
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required',
            ],
        );
        return $validator;
    }

    // validate uopdate
    function validateLogin($data)
    {
        $validator = Validator::make(
            $data,
            [
                'email' => 'required|email',
                'password' => 'required',
            ],
        );
        return $validator;
    }
}

?>