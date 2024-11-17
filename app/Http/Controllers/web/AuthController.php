<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }


    public function login(loginRequest $request)
    {
        $this->authService->login($request->only(['email', 'password']));
        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
         Auth::logout();
        return view('login');

    }
}

