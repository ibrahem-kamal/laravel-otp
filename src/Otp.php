<?php

namespace Ibrahemkamal\Otp;

use Ibrahemkamal\Otp\Concerns\ServiceResponse;
use Ibrahemkamal\Otp\Contracts\HasOtp;
use Ibrahemkamal\Otp\Models\OtpCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Otp
{
    private array $generatorOptions = [];

    private string $service;

    private ?HasOtp $model = null;

    private bool $validateUniquenessAfterGeneration;

    private ServiceResponse $serviceResponse;

    private string $phone;

    /**
     * @throws \Exception
     */
    public function __construct(ServiceResponse $serviceResponse)
    {
        $this->serviceResponse = $serviceResponse;
        $this->setService();
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

    public function generate($otp = null): OtpCode
    {
        if (! $this->model) {
            throw new \Exception('Model is required to generate otp');
        }
        $otp = $otp ?? $this->generatePassword();
        if (! $this->validateOtpUniqueness($otp)) {
            return $this->generate();
        }

        return $this->model->otpCodes()->create([
            'otp' => $otp,
            'phone' => $this->getPhone(),
            'service' => $this->service,
            'expires_at' => now()->addMinutes(config('otp.services.'.$this->service.'.expires_in')),
        ]);
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
        if (! config('otp.services.'.$service)) {
            throw new \Exception('Service not found in the config file');
        }
        $this->service = $service;
        $this->setDefaults();

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

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(HasOtp $model): static
    {
        $this->model = $model;
        $this->setPhone($model->getPhoneNumber());

        return $this;
    }

    private function validateOtpUniqueness(string $otp): bool
    {
        if (! $this->isValidateUniquenessAfterGeneration()) {
            return true;
        }

        return ! $this->model->otpCodes()->where('phone', $this->phone)
            ->where('service', $this->service)->where('otp', $otp)->exists();
    }

    public function verifyOtp(string $otp): ServiceResponse
    {
        if (! $this->model) {
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

    private function setDefaults(): void
    {
        $generatorOptions = config('otp.services.'.$this->service.'.otp_generator_options');

        if ($generatorOptions) {
            $this->setGeneratorOptions(
                length: $generatorOptions['length'],
                numbers: $generatorOptions['numbers'],
                letters: $generatorOptions['letters'],
                symbols: $generatorOptions['symbols'],
            );
        } else {
            $this->setGeneratorOptions(
                length: config('otp.fallback_options.otp_generator_options.length'),
                numbers: config('otp.fallback_options.otp_generator_options.numbers'),
                letters: config('otp.fallback_options.otp_generator_options.letters'),
                symbols: config('otp.fallback_options.otp_generator_options.symbols'),
            );
        }
        $this->setValidateUniquenessAfterGeneration(config('otp.services.'.$this->service.'.validate_uniqueness_after_generation') ?? config('otp.fallback_options.validate_uniqueness_after_generation'));
    }

    public function getGeneratorOptions(): array
    {
        return $this->generatorOptions;
    }

    protected function generatePassword(): string
    {
        $otp = Str::password(...$this->generatorOptions);

        return $otp;
    }
}
