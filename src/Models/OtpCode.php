<?php

namespace Ibrahemkamal\Otp\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class OtpCode
 * @package Ibrahemkamal\Otp\Models
 * @property string $otp
 * @property string $phone
 * @property string $service
 * @property \DateTime $expires_at
 * @property \DateTime $verified_at
 */
class OtpCode extends Model
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

    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('verified_at');
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }
}
