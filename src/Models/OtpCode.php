<?php

namespace Ibrahemkamal\Otp\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class OtpCode
 *
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
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
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
