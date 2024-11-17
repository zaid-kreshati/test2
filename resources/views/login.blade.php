


@extends('layouts.PostBlug_header')

@section('title', __('login'))

@section('content')


        <div class="card">
            <div class="card-header2">
                <h3>{{ __('Login') }}</h3>
            </div>
            <div class="card-body2">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" placeholder="{{ __('Email') }}" id="email" name="email" required autofocus>
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="form-group">
                        <input type="password" placeholder="{{ __('Password') }}" id="password" name="password" required>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>




            




                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember">
                        <a for="remember">{{ __('Remember Me') }}</a>
                    </div>

                    <div class="center">
                        <button type="submit" class="btn4">{{ __('Signin') }}</button>
                    </div>
                </form>
            </div>
        </div>

@endsection




