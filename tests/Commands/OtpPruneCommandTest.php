<?php


test('it can prune expired otp codes', function () {
    $this->testUser->otp()->generate();
    $this->travel(6)->minutes();
    $this->artisan('otp:prune --expired')
        ->assertExitCode(0);
    expect($this->testUser->otpCodes)->toBeEmpty();
});

test('it can prune verified otp codes', function () {
    $code = $this->testUser->otp()->generate();
    $this->testUser->otp()->verifyOtp($code->otp);
    $this->artisan('otp:prune --verified')
        ->assertExitCode(0);
    expect($this->testUser->otpCodes)->toBeEmpty();
});

