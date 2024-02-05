<?php

use Ibrahemkamal\Otp\Models\OtpCode;

test('it generates otp code and stores it in the database', function () {
    $this->testUser->otp()->generate();
    expect(OtpCode::count())->toBe(1);
});
test('it verifies the otp code', function () {
    $this->freezeTime();
    $otp = $this->testUser->otp()->generate();
    $this->testUser->otp()->verifyOtp($otp->otp);
    expect($otp->fresh()->verified_at->toDateTimeString())->toEqual(now()->toDateTimeString());
});
test('it returns false status if the otp code is expired', function () {
    $this->freezeTime();
    $otp = $this->testUser->otp()->generate();
    $this->travel(6)->minutes();
    $response = $this->testUser->otp()->verifyOtp($otp->otp);
    expect($response->isSuccess())->toBeFalse();
});

test('it returns false status if the otp code is not found', function () {
    $response = $this->testUser->otp()->verifyOtp('1234');
    expect($response->isSuccess())->toBeFalse();
});

test('it returns false status if the otp code is already verified', function () {
    $otp = $this->testUser->otp()->generate();
    $this->testUser->otp()->verifyOtp($otp->otp);
    $response = $this->testUser->otp()->verifyOtp($otp->otp);
    expect($response->isSuccess())->toBeFalse();
});
