<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\Auth\loginRequest;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $authService;


    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('DashBoard.register');
    }

    public function showLoginForm()
    {
        return view('DashBoard.login');
    }


    public function login(loginRequest $request)
    {
        $this->authService->login($request->only(['email', 'password']));
        return view('DashBoard.home');
    }

    public function logout(Request $request)
    {
         Auth::logout();
        return view('DashBoard.login');

    }

    public function index()
    {
        return view('DashBoard.home');
    }
}

