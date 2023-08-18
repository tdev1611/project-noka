<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Notifications\AdminResetPasswordNotification;
use App\Mail\ResetPassWordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    // user
    public function showForgotPasswordForm()
    {
        return view('auth.user.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        try {
            $email = $request->only('email');
            $validator = Validator::make(
                $email,
                [
                    'email' => 'required|exists:users,email',
                ],
            );
            if ($validator->fails()) { {
                    throw new \Exception('Email không tồn tại trên hệ thống. ');
                }
            }
            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $status === Password::RESET_LINK_SENT
                ? back()->with('success', 'Đã gửi link reset tới email của bạn')
                : back()->withErrors(['email' => __($status)])->withInput();
            // $token = Str::random(40);
            // $email = $email
            // Mail::to($email)->send(new ResetPasswordMail($token, $email));
            // return redirect(route('user.forget_password'))->with('success', 'Send password reset link to your email');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }
    // administrator



    public function adminShowForgotPasswordForm()
    {
        return view('auth.admin.forgot-password');
    }

    public function adminSendResetLink(Request $request)
    {
        try {
            $email = $request->only('email');
            $validator = Validator::make(
                $email,
                [
                    'email' => 'required|exists:admins,email',
                ],
            );
            if ($validator->fails()) { {
                    throw new \Exception('Email không tồn tại trên hệ thống. ');
                }
            }
            $status = Password::broker('admins')->sendResetLink(
                $request->only('email')
            );
            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('success', 'Đã gửi link reset tới email của bạn');
            } else {
                return redirect()->back()->with('error', __($status));
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }
    // return $status === Password::RESET_LINK_SENT
    //     ? back()->with('success', 'Đã gửi link reset tới email của bạn')
    //     : back()->withErrors(['email' => __($status)])->withInput();
}