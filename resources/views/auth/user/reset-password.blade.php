@extends('auth.user.layout')
@section('content')

    <form class="login100-form validate-form" method="POST" action="{{ route('user.password.update') }}">
        @csrf
        @if (session('success'))
            <div class="text-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="text-danger">
                {{ session('error') }}
            </div>
        @endif
      
        <span class="login100-form-title">
            Reset password
        </span>
        <input type="hidden" name="token" value="{{ $token }}">
       
        <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input id="email" type="email" class="input100 @error('email') is-invalid @enderror"
            name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus  placeholder="Email">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input class="input100" type="password" name="password" placeholder="Password">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="wrap-input100 validate-input" data-validate="Password is required">
            <input class="input100" type="password" name="password_confirmation" placeholder="Password comfirmation">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
                <i class="fa fa-lock" aria-hidden="true"></i>
            </span>
            @error('password_confirmation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
                Update password
            </button>
        </div>




    </form>
@endsection
