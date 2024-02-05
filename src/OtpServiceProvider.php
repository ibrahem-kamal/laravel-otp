<?php

namespace Ibrahemkamal\Otp;

use Ibrahemkamal\Otp\Commands\OtpPruneCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OtpServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-otp')
            ->hasConfigFile()
            ->hasMigration('create_laravel-otp_table')
            ->hasCommand(OtpPruneCommand::class);
    }
}
