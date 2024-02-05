<?php

namespace Ibrahemkamal\Otp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class
OtpCode extends Model
{
    protected $fillable = ['otp', 'phone', 'service', 'expires_at', 'verified_at'];
    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function relatable(): MorphTo
    {
        return $this->morphTo();
    }
}
