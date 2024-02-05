# laravel-otp

___ 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ibrahem-kamal/laravel-otp.svg?style=flat-square)](https://packagist.org/packages/ibrahem-kamal/laravel-otp)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ibrahem-kamal/laravel-otp/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ibrahem-kamal/laravel-otp/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ibrahem-kamal/laravel-otp/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ibrahem-kamal/laravel-otp/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ibrahem-kamal/laravel-otp.svg?style=flat-square)](https://packagist.org/packages/ibrahem-kamal/laravel-otp)

Laravel Otp is designed to generate and verify otp being sent to users

## Installation

You can install the package via composer:

```bash
composer require ibrahem-kamal/laravel-otp
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-otp-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-otp-config"
```

This is the contents of the published config file:

```php
return [
    'services' => [
        'default' => [
            'expires_in' => 5, // in minutes
            'otp_generator_options' => [
                'length' => 4, // no of digits
                'numbers' => true,
                'letters' => false,
                'symbols' => false,
            ],
            'validate_uniqueness_after_generation' => true,
            'delete_after_verification' => false,
        ],
    ],
    'fallback_options' => [
        'otp_generator_options' => [
            'length' => 4, // no of digits
            'numbers' => true,
            'letters' => false,
            'symbols' => false,
        ],
        'validate_uniqueness_after_generation' => true, // whether to validate the uniqueness of the otp after generation by checking the database
        'delete_after_verification' => false, // whether to delete the otp after verification
    ]

];
```

> You can add as many services as you want, and you can use the fallback options to set the default options for the otp
> generation and verification


## Usage

- First you need to prepare your model by implementing the `HasOtp` Interface and using the `InteractsWithOtp` trait
```php
class User extends Authenticatable implements HasOtp
{
    use InteractsWithOtp;
}
```

> If you dont have `phone` column in your model, you can override the `getPhoneNumber` method to return the user phone
> number like this

```php
    public function getPhoneNumber(): string
    {
        return $this->mobile_number;
    }
```

- Then you can use the `Otp` to generate otp

```php
$user->otp()->generate() // returns OtpCode Model instance

// you can also pass the service name to generate otp for a specific service or modify the options

$user->otp()
        ->setPhone('11111')
        ->setValidateUniquenessAfterGeneration(false)
        ->setService('other service')
        ->setGeneratorOptions(
            length: 6,
            letters: false,
            numbers: true, 
            symbols: false
        )->generate()  // returns OtpCode Model instance
```

- You can verify the otp using the `verify` method

```php
$otp = $user->otp()->verifyOtp('1234') // returns ServiceResponse instance
    $otp->isSuccess(); //bool
    $otp->getErrorsString(); // errors as string
    $otp->getErrors(); // errors as array
    $otp->getData(); // OtpCode Model instance when success
    $otp->toArray(); // array of all the above
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [ibrahemkamal](https://github.com/ibrahem-kamal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
