<?php

namespace Ibrahemkamal\Otp;

use Ibrahemkamal\Otp\Concerns\ServiceResponse;
use Ibrahemkamal\Otp\Models\OtpCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Otp
{
    private array $generatorOptions = [];
    private OtpCode $otpCode;
    private string $service;
    private ?Model $model;
    private bool $validateUniquenessAfterGeneration;
    private ServiceResponse $serviceResponse;
    private string $phone;

    public function __construct(ServiceResponse $serviceResponse)
    {
        $this->setService();
        $this->serviceResponse = $serviceResponse;
        $this->setDefaults();
    }

    public function isValidateUniquenessAfterGeneration(): bool
    {
        return $this->validateUniquenessAfterGeneration;
    }

    public function setValidateUniquenessAfterGeneration(bool $validateUniquenessAfterGeneration): static
    {
        $this->validateUniquenessAfterGeneration = $validateUniquenessAfterGeneration;
        return $this;
    }

    public function generate(): OtpCode
    {
        if (!$this->model) {
            throw new \Exception('Model is required to generate otp');
        }
        $otp = Str::password(...$this->generatorOptions);
        $this->otpCode = $this->model->otpCodes()->create([
            'otp' => $otp,
            'phone' => $this->phone,
            'service' => $this->service,
            'expires_at' => now()->addMinutes(config('otp.services.' . $this->service . '.expires_in')),
        ]);
        return $this->validateOtpUniqueness();
    }

    public function setGeneratorOptions($length = 4, $numbers = true, $letters = false, $symbols = false): static
    {
        $this->generatorOptions = [
            'length' => $length,
            'letters' => $letters,
            'numbers' => $numbers,
            'symbols' => $symbols,
        ];
        return $this;
    }

    public function setService(string $service = 'default'): static
    {
        if (!config('otp.services.' . $service)) {
            throw new \Exception('Service config not found');
        }
        $this->service = $service;
        return $this;
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): static
    {
        $this->model = $model;
        return $this;
    }

    private function validateOtpUniqueness(): OtpCode
    {
        if (!$this->isValidateUniquenessAfterGeneration()) {
            return $this->otpCode;
        }
        return $this->model->otpCodes()->where('id', '!=', $this->otpCode->id)->where('phone', $this->phone)
            ->where('service', $this->service)->where('otp', $this->otpCode->otp)->exists() ? $this->generate() : $this->otpCode;
    }

    public function verifyOtp(string $otp): ServiceResponse
    {
        if (!$this->model) {
            throw new \Exception('Model is required to verify otp');
        }
        $otpCode = $this->model->otpCodes()->where('otp', $otp)->where('phone', $this->phone)
            ->where('service', $this->service)
            ->whereNull('verified_at')
            ->first();
        if ($otpCode) {
            if ($otpCode->expires_at->isPast()) {
                return $this->serviceResponse->setSuccess(false)->setErrors(['otp' => __('OTP has expired')]);
            }
            if (config('otp.delete_after_verification')) {
                $otpCode->delete();
            } else {
                $otpCode->update(['verified_at' => now()]);
            }
            $otpCode->verified_at = now();
            return $this->serviceResponse->setSuccess(true)->setData($otpCode);
        }
        return $this->serviceResponse->setSuccess(false)->setErrors(['otp' => __('Invalid OTP')]);
    }

    private function setDefaults()
    {
        $generatorOptions = config('otp.services.' . $this->service . '.otp_generator_options');
        if ($generatorOptions) {
            $this->setGeneratorOptions(
                length: $generatorOptions['length'],
                numbers: $generatorOptions['numbers'],
                letters: $generatorOptions['letters'],
                symbols: $generatorOptions['symbols'],
            );
        } else {
            $this->setGeneratorOptions(
                length: config('otp.otp_generator_options.fallback_options.length'),
                numbers: config('otp.otp_generator_options.fallback_options.numbers'),
                letters: config('otp.otp_generator_options.fallback_options.letters'),
                symbols: config('otp.otp_generator_options.fallback_options.symbols'),
            );
        }
        $this->setValidateUniquenessAfterGeneration(config('otp.services.' . $this->service . '.validate_uniqueness_after_generation') ?? config('otp.fallback_options.validate_uniqueness_after_generation'));
    }
}
