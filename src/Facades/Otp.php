<?php

namespace Ibrahemkamal\Otp\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ibrahemkamal\Otp\Otp
 *
 * @method static \Ibrahemkamal\Otp\Otp setModel(\Illuminate\Database\Eloquent\Model $model)
 * @method static \Ibrahemkamal\Otp\Otp getModel(string $phone)
 * @method static \Ibrahemkamal\Otp\Otp setPhone(string $phone)
 * @method static \Ibrahemkamal\Otp\Otp getPhone(string $phone)
 * @method static \Ibrahemkamal\Otp\Otp setService(string $service = 'default')
 * @method static \Ibrahemkamal\Otp\Otp getService(string $service = 'default')
 * @method static \Ibrahemkamal\Otp\Otp setGeneratorOptions($length = 32, $letters = true, $numbers = true, $symbols = true)
 * @method static \Ibrahemkamal\Otp\Models\OtpCode generate()
 * @method static \Ibrahemkamal\Otp\Otp verifyOtp()
 * @method static \Ibrahemkamal\Otp\Otp isValidateUniquenessAfterGeneration()
 * @method static \Ibrahemkamal\Otp\Otp setValidateUniquenessAfterGeneration()
 */
class Otp extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Ibrahemkamal\Otp\Otp::class;
    }
}
