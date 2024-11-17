@extends('DashBoard.header')

@section('title', __('Register'))

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
    <div class="card">
        <div class="card-header2">
            <h3>{{ __('Register') }}</h3>
        </div>
        <div class="card-body2">
            <form id="registrationForm" action="{{ route('verify.two.factor.code') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-3">
                    <input type="text" placeholder="{{ __('Name') }}" id="name" class="form-control"
                        name="name" required autofocus>
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <input type="text" placeholder="{{ __('Email') }}" id="email_address" class="form-control"
                        name="email" required autofocus>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <input type="password" placeholder="{{ __('Password') }}" id="password" class="form-control"
                        name="password" required>
                    @if ($errors->has('password'))
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <input type="password" placeholder="{{ __('Confirm Password') }}" id="password_confirmation"
                        class="form-control" name="password_confirmation" required>
                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>

                <div class="form-group mb-3">
                    <input type="hidden" name="role" value="admin">
                </div>



                <div class="center">
                    <button type="submit" class="btn4">{{ __('Sign up') }}</button>
                </div>
            </form>

            @if (session('error'))
                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
            @endif
        </div>
    </div>

@endsection
