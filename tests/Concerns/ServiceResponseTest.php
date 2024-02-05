<?php

use Ibrahemkamal\Otp\Concerns\ServiceResponse;
use Illuminate\Contracts\Support\Arrayable;

test('it can set error as string', function () {
    $service = app(ServiceResponse::class)->setErrors('test error');
    expect($service->getErrorsString())->toContain('test error');
});
test('it can set errors as array', function () {
    $service = app(ServiceResponse::class)->setErrors(['test error']);
    expect($service->getErrorsString())->toContain('test error');
});

test('it can get errors as array', function () {
    $service = app(ServiceResponse::class)->setErrors(['test error']);
    expect($service->getErrorsArray())->toBeArray()->toContain('test error');
});
test('it can get errors as string', function () {
    $service = app(ServiceResponse::class)->setErrors(['test error']);
    expect($service->getErrorsString())->toBeString()->toContain('test error');
});
test('it can set messages as string', function () {
    $service = app(ServiceResponse::class)->setMessages('test message');
    expect($service->getMessages())->toContain('test message');
});
test('it can set messages as array', function () {
    $service = app(ServiceResponse::class)->setMessages(['test message']);
    expect($service->getMessages())->toContain('test message');
});

test('it can get messages as array', function () {
    $service = app(ServiceResponse::class)->setMessages(['test message']);
    expect($service->getMessages())->toBeArray()->toContain('test message');
});
test('it can get messages as string', function () {
    $service = app(ServiceResponse::class)->setMessages(['test message']);
    expect($service->getMessagesString())->toBeString()->toContain('test message');
});

test('it can set data as array', function () {
    $service = app(ServiceResponse::class)->setData(['test data']);
    expect($service->getData())->toBeArray()->toContain('test data');
});

test('it can get data', function () {
    $service = app(ServiceResponse::class)->setData(['test data']);
    expect($service->getData())->toBeArray()->toContain('test data');
});
test('it can get specific data key', function () {
    $service = app(ServiceResponse::class)->setData([
        'test-data' => 'test value',
        'test-data-2' => 'test value 2',
    ]);
    expect($service->getData('test-data'))->toBeString()->toContain('test value');
});
test('it returns null if data key doesnt exist', function () {
    $service = app(ServiceResponse::class)->setData([
        'test-data' => 'test value',
        'test-data-2' => 'test value 2',
    ]);
    expect($service->getData('test-data-3'))->toBeNull();
});
test('it sets success value', function () {
    $service = app(ServiceResponse::class)->setSuccess(true);
    expect($service->isSuccess())->toBeTrue();
});
test('it returns success value', function () {
    $service = app(ServiceResponse::class)->setSuccess(true);
    expect($service->isSuccess())->toBeTrue();
});
test('it can make response', function () {
    $service = app(ServiceResponse::class)->make();
    expect($service)->toBeInstanceOf(ServiceResponse::class);
});
test('it implements Arrayable interface', function () {
    $service = app(ServiceResponse::class)->make();
    expect($service)->toBeInstanceOf(Arrayable::class);
});
test('it can return all keys in toArray function', function () {
    $service = app(ServiceResponse::class)->make();
    expect($service->toArray())->toBeArray()
        ->toHaveKeys([
            'success',
            'data',
            'errors',
            'messages',
        ]);
});
