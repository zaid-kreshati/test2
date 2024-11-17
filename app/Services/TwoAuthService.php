<?php


namespace App\Services;

use App\Repositories\TwoAuthRepository;
class TwoAuthService
{
    protected $twoAuthRepository;

    public function __construct(TwoAuthRepository $twoAuthRepository)
    {
        $this->twoAuthRepository = $twoAuthRepository;
    }

    public function initiateRegistration(array $request)
    {
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $registrationData['verification_code'] = $verificationCode;
        $registrationData['expires_at'] = now()->addMinutes(10);
        $registrationData['email'] = $request['email'];
        $registrationData['name'] = $request['name'];
        $registrationData['password'] = $request['password'];
        $registrationData['role'] = $request['role'];

        $this->twoAuthRepository->create($registrationData);
        return ;
    }

    public function verifyRegistration(array $request)
    {
        $registrationData = $this->twoAuthRepository->getRegistrationData($request['two_factor_code']);
        return $registrationData;
    }

    public function resendTwoFactorCode()
    {
        $this->twoAuthRepository->resendTwoFactorCode();
        return ;
    }
}
