@extends('layouts.PostBlug_header')

@section('content')
            <div class="card">
                <div class="card-header2">{{ __('Two-Factor Authentication') }}</div>

                <div class="card-body2">
                    <form method="POST" action="{{ route('register.verify') }}">
                        @csrf

                        <div class="mb-4 text-center">
                            <p>{{ __('A verification code has been sent to your email address.') }}</p>
                            <p>{{ __('Please enter the code below to complete the login process.') }}</p>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="two_factor_code" class="col-md-4 col-form-label text-md-right">{{ __('Verification Code') }}</label>

                            <div class="col-md-6">
                                <input id="two_factor_code" type="text" class="form-control @error('two_factor_code') is-invalid @enderror" name="two_factor_code" required autocomplete="off" autofocus>

                                @error('two_factor_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verify') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
@endsection
