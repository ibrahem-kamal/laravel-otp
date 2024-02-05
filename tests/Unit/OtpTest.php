<?php

use Ibrahemkamal\Otp\Facades\Otp;

test('it throws an error if the service doesnt exist in the config file', function () {
    expect(Otp::setService('test service'));
})->throws(Exception::class, 'Service not found in the config file');

test('it sets default service if no other service has been set', function () {
    expect(Otp::setService()->getService())->toBe('default');
});

test('it sets the service', function () {
    config(['otp.services.test' => ['expires_in' => 5]]);
    expect(Otp::setService('test')->getService())->toBe('test');
});

test('it sets the phone', function () {
    expect(Otp::setPhone('1234567890')->getPhone())->toBe('1234567890');
});

test('it sets the model', function () {
    expect(Otp::setModel($this->testUser)->getModel())->toBe($this->testUser);
});

test('it sets the generator options', function () {
    expect(Otp::setGeneratorOptions(4, true, false, false)->getGeneratorOptions())->toBe([
        'length' => 4,
        'letters' => false,
        'numbers' => true,
        'symbols' => false,
    ]);
});

test('it sets the default otp generation options if none has been set for the service', function () {
    $options = [
        'length' => 10,
        'letters' => true,
        'numbers' => true,
        'symbols' => true,
    ];
    config(['otp.fallback_options.otp_generator_options' => $options]);
    config(['otp.services.test' => ['expires_in' => 5]]);
    expect(Otp::setService('test')->getGeneratorOptions())->toBe($options);
});

test('it sets validate uniqueness feature', function () {
    expect(Otp::setValidateUniquenessAfterGeneration(false)->isValidateUniquenessAfterGeneration())->toBeFalse();
});

test('it throws exception if generate method is called with no model', function () {
    Otp::generate();
})->throws(Exception::class, 'Model is required to generate otp');

test('it throws exception if verifyOtp method is called with no model', function () {
    Otp::verifyOtp('1234');
})->throws(Exception::class, 'Model is required to verify otp');

test('it can return latest code', function () {
    $user = $this->testUser;
    $code = $user->otp()->generate();
    expect($user->latestOtpCode->id)->toBe($code->id);
});
