<?php

namespace Ibrahemkamal\Otp\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;

class ServiceResponse implements Arrayable
{
    protected array $errors = [];

    protected array $messages = [];

    protected bool $success = false;

    protected mixed $data = [];

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array|string $messages): ServiceResponse
    {
        $this->messages = Arr::wrap($messages);

        return $this;
    }

    public function __construct(bool $success = true, array $data = [], array $errors = [], array $messages = [])
    {
        $this->success = $success;
        $this->data = $data;
        $this->errors = $errors;
        $this->messages = $messages;
    }

    public static function make(bool $success = true, array $data = [], array $errors = [], array $messages = []): self
    {
        return new self();
    }

    public function setSuccess(bool $status): self
    {
        $this->success = $status;

        return $this;
    }

    public function setErrors(array|string $errors): self
    {
        $this->errors = Arr::wrap($errors);

        return $this;
    }

    public function setData(mixed $data = []): self
    {
        $this->data = $data;

        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getErrorsArray(): array
    {
        return $this->errors;
    }

    public function getErrorsString(): string
    {
        return implode(PHP_EOL, $this->errors);
    }

    public function getMessagesString(): string
    {
        return implode(PHP_EOL, $this->messages);
    }

    public function getData(?string $key = null): mixed
    {
        $isObject = is_object($this->data);
        if ($key) {
            return $isObject ? (property_exists($this->data, $key) ? $this->data->$key : null) : ($this->data[$key] ?? null);
        }

        return $this->data;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->isSuccess(),
            'errors' => $this->getErrorsArray(),
            'errorsString' => $this->getErrorsString(),
            'messages' => $this->getMessages(),
            'messagesString' => $this->getMessagesString(),
            'data' => $this->getData(),
        ];
    }
}
