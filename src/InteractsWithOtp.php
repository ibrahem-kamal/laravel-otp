<?php

namespace Ibrahemkamal\Otp;

use Ibrahemkamal\Otp\Facades\Otp;
use Ibrahemkamal\Otp\Models\OtpCode;

trait InteractsWithOtp
{
    public function otpCodes()
    {
        return $this->morphMany(OtpCode::class, 'relatable', 'model_type', 'model_id');
    }

    public function latestOtpCode()
    {
        return $this->morphOne(OtpCode::class, 'relatable', 'model_type', 'model_id')->latestOfMany();
    }

    public function otp(): \Ibrahemkamal\Otp\Otp
    {
        return Otp::setModel($this)->setPhone($this->getPhoneNumber());
    }

    public function getPhoneNumber(): string
    {
        return $this->phone;
    }
}
