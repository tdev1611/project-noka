<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{


    //user
    public function showResetPasswordForm($token)
    {
        return view('auth.user.reset-password', ['token' => $token]);
    }


    public function resetPassword(Request $request)
    {
        try {
            $input = $request->only('email', 'password', 'password_confirmation', 'token');
            $validator = Validator::make(
                $input,
                [
                    'token' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|confirmed|min:8',
                ],
            );
            if ($validator->fails()) { {
                    throw new \Exception('Reset fails');
                }
            }
            $status = Password::reset(
                $input,
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );
            if ($status == Password::PASSWORD_RESET) {
                return redirect(route('login'))->with('success', 'Reset Password Success');
            }
            // return $status == Password::PASSWORD_RESET
            //     ?? redirect()->route('user.login')->with('success', __($status));

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }

    // administrator
    public function adminShowResetPasswordForm($token)
    {
        return view('auth.admin.reset-password', ['token' => $token]);
    }



    public function adminresetPassword(Request $request)
    {
        try {
            $input = $request->only('email', 'password', 'password_confirmation', 'token');
            $validator = Validator::make(
                $input,
                [
                    'token' => 'required',
                    'email' => 'required|email',
                    'password' => 'required|confirmed|min:8',
                ],
            );
            if ($validator->fails()) { {
                    throw new \Exception('Reset fails');
                }
            }
            $status = Password::broker('admins')->reset(
                $input,
                function ($admin, $password) {
                    $admin->forceFill([
                        'password' => Hash::make($password),
                    ])->save();
                }
            );
            if ($status == Password::PASSWORD_RESET) {
                return redirect(route('admin.login.form'))->with('success', 'Reset Password Success');
            }

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }
}