<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\web\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\Auth\registerRequest;
use App\Services\TwoAuthService;
use App\Services\AuthService;

class TwoAuthController extends Controller
{
    protected $twoAuthService;
    protected $authService;
    public function __construct(TwoAuthService $twoAuthService, AuthService $authService)
    {
        $this->twoAuthService = $twoAuthService;
        $this->authService = $authService;
    }

    public function initiateRegistration(registerRequest $request)
    {
        $this->twoAuthService->initiateRegistration($request->only(['name', 'email', 'password','role']));
        return redirect()->route('verify.two.factor');
    }


    public function verifyRegistration(Request $request)
    {
        $response=$this->twoAuthService->verifyRegistration($request->only(['two_factor_code']));

        if($response['status']==false){
            return redirect()->route('verify.two.factor')
            ->with('error', $response['error']);
        }
        $user=$this->authService->register($response);



        if($response['role']=='admin'){
            return view('DashBoard.login');
        }
        else{
            return view('login');
        }
    }

public function resendTwoFactorCode()
{
    $this->twoAuthService->resendTwoFactorCode();

        return response()->json([
            'success' => true,
            'message' => 'Verification code resent'
        ]);
    }

    public function showVerifyForm()
    {
        return view('two-factor-code');
    }

}

