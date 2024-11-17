<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Carbon\Carbon;

class TwoAuthRepository
{
    public function create(array $data)
    {
        Cache::put('registration',$data, now()->addMinutes(10));
        Mail::to($data['email'])->send(new TwoFactorCodeMail($data['verification_code']));
        return ;
    }

    public function getRegistrationData($two_factor_code)
    {

        $registrationData = Cache::get('registration');
        if (!$registrationData) {
            $response['error']='Registration failed. Please try again.';
            $response['status']=false;
            return $response;
        }

        if ($two_factor_code !== $registrationData['verification_code']) {
            $response['error']='Invalid verification code.';
            $response['status']=false;
            return $response;
        }

        if (now()->isAfter(Carbon::parse($registrationData['expires_at']))) {
            Cache::forget('registration');
            $response['error']='Verification code expired. Please try again.';
            $response['status']=false;
            return $response;
        }

        // Clean up
        Cache::forget('registration');

        $registrationData['status']=true;
        return $registrationData;
    }

    public function resendTwoFactorCode()
    {
        $registrationData = Cache::get('registration');



    // Generate new verification code
    $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    // Update cache with new code
    $registrationData['verification_code'] = $verificationCode;
    $registrationData['expires_at'] = now()->addMinutes(10);
    Cache::put('registration', $registrationData, now()->addMinutes(10));

    // Send new verification email
    Mail::to($registrationData['email'])->send(new TwoFactorCodeMail($verificationCode));


        return ;
    }

}
