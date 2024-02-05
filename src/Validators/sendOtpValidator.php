<?php

namespace Ibrahemkamal\Otp\Validators;

use Ibrahemkamal\Otp\Contracts\sendOtpValidator as SendOtpValidatorContract;

class sendOtpValidator implements SendOtpValidatorContract
{

    public function canSendOtp(string $phone, string $service = 'default'): bool
    {
        // TODO: Implement canSendOtp() method.
    }
}
