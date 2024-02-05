<?php

namespace Ibrahemkamal\Otp\Tests\TestModels;

use Ibrahemkamal\Otp\Contracts\HasOtp;
use Ibrahemkamal\Otp\InteractsWithOtp;
use Illuminate\Database\Eloquent\Model;

class TestUser extends Model implements HasOtp
{
    use InteractsWithOtp;

    protected $guarded = [];

    public $timestamps = false;
}
