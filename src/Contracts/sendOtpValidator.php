<?php

namespace Ibrahemkamal\Otp\Contracts;

interface sendOtpValidator
{
    public function canSendOtp(string $phone, string $service = 'default'): bool;
}
