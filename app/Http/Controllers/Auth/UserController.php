<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart as CartSession;
use App\Models\Cart;

use App\Models\User;

class UserController extends Controller
{


    protected $userService;
    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    function showRegistrationForm()
    {
        return view('auth.user.register');
    }
    //register
    function store(Request $request)
    {
        try {
            $data = $this->userService->formRequestAll();
            $validator = $this->userService->validateStore($data);
            if ($validator->fails()) { {
                    throw new \Exception('Đăng ký không thành công. ');
                }
            }
            $password = Hash::make($request->input('password'));
            $data['password'] = $password;
            $user = $this->userService->register($data);
            $message = 'Đăng ký thành công ';
            Auth::login($user);
            return redirect(route('login'))->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }

    function showLoginForm()
    {
        if (Auth::user()) {
            return redirect()->back()->with('success', 'You logged in successfully');
        }
        return view('auth.user.login');
    }

    function login(Request $request)
    {
        try {
            $data = $this->userService->formRequestLogin();
            $validator = $this->userService->validateLogin($data);
            if ($validator->fails()) { {
                    throw new \Exception('Đăng nhập không thành công. ');
                }
            }
            if (Auth::guard('web')->attempt($data)) {
                $cartItem = CartSession::content();
                foreach ($cartItem as $item) {
                    Cart::updateOrCreate([
                        'rowId' => $item->rowId,
                        'user_id' => Auth::user()->id,
                        'name' => $item->name,
                        'product_id' => $item->id,
                        'qty' => $item->qty,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal,
                        'options' => json_encode($item->options),
                    ]);
                }
                CartSession::destroy();
                return redirect(route('home'))->with('success', 'Đăng nhập thành công');
            } else {
                return redirect()->back()->with('error', 'Đăng nhập không thành công. Vui lòng kiểm tra lại email và mật khẩu.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
        }
    }


    function logout(Request $request)
    {
        Auth::logout();
        return redirect(route('login'));
    }


    // function forget_password()
    // {
    //     return view('auth.user.forget-password');
    // }

    // function sendMail_reset_password(Request $request)
    // {
    //     try {
    //         $email = $request->only('email');
    //         $validator = Validator::make(
    //             $email,
    //             [
    //                 'email' => 'required|exists:users,email',
    //             ],
    //         );
    //         if ($validator->fails()) { {
    //                 throw new \Exception('Email không tồn tại trên hệ. ');
    //             }
    //         }

    //         $response = Password::sendResetLink(
    //             $request->only('email')
    //         );

    //         if ($response === Password::RESET_LINK_SENT) {
    //             return redirect()->back()->with('success', trans($response));
    //         } else {
    //             throw new \Exception(trans($response));
    //         }
    //         // $token = Str::random(40);
    //         // Mail::to($email)->send(new ResetPasswordMail($token));
    //         // return redirect(route('user.forget_password'))->with('success', 'Send password reset link to your email');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withErrors($validator)->with('error', $e->getMessage())->withInput();
    //     }

    // }


    // public function form_reset_password(Request $request, $token = null)
    // {
    //     return view('auth.user.reset-password')->with(
    //         ['token' => $token, 'email' => $request->email]
    //     );
    // }

    // protected function reset_password(Request $request)
    // {
    //     $request->validate([
    //         'token' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|confirmed|min:8', // Điều chỉnh các quy tắc theo yêu cầu của bạn
    //     ]);

    //     $response = Password::reset(
    //         $request->only('email', 'password', 'password_confirmation', 'token'),
    //         function ($user, $password) {
    //             $user->forceFill([
    //                 'password' => Hash::make($password),
    //             ])->save();
    //         }
    //     );

    //     if ($response == Password::PASSWORD_RESET) {
    //         return redirect(route('login'))->with('status', trans($response));
    //     } else {
    //         throw new \Exception(trans($response));
    //     }
    // }

}