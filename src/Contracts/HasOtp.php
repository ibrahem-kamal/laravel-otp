<?php

namespace Ibrahemkamal\Otp\Contracts;

use Ibrahemkamal\Otp\Otp;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface HasOtp
{
    public function otpCodes(): MorphMany;

    public function latestOtpCode(): MorphOne;

    public function otp(): Otp;

    public function getPhoneNumber(): string;
}
