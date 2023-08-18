@extends('auth.user.layout')
@section('content')
    <form class="login100-form validate-form" method="POST" action="{{ route('user.password.email') }}">
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

        <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
            <input class="input100" type="text" name="email" placeholder="Email">
            <span class="focus-input100"></span>
            <span class="symbol-input100">
                <i class="fa fa-envelope" aria-hidden="true"></i>
            </span>
            @error('email')
                <small>
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                </small>
            @enderror
        </div>

        <div class="container-login100-form-btn">
            <button class="login100-form-btn" type="submit">
                Send Link
            </button>
        </div>




    </form>
@endsection
