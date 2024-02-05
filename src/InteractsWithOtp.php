<?php

namespace Ibrahemkamal\Otp;

use Ibrahemkamal\Otp\Facades\Otp;
use Ibrahemkamal\Otp\Models\OtpCode;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait InteractsWithOtp
{
    public function otpCodes(): MorphMany
    {
        return $this->morphMany(OtpCode::class, 'relatable', 'model_type', 'model_id');
    }

    public function latestOtpCode(): MorphOne
    {
        return $this->morphOne(OtpCode::class, 'relatable', 'model_type', 'model_id')->latestOfMany();
    }

    public function otp(): \Ibrahemkamal\Otp\Otp
    {
        return Otp::setModel($this);
    }

    public function getPhoneNumber(): string
    {
        return $this->phone;
    }
}
