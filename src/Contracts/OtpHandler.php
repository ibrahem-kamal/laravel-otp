<?php

namespace Ibrahemkamal\Otp\Contracts;

use Ibrahemkamal\Otp\Models\OtpCode;

interface OtpHandler
{
    public function handle(OtpCode $otpCode);
}
