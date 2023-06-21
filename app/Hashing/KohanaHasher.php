<?php

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Hashing\AbstractHasher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;

class KohanaHasher extends AbstractHasher implements Hasher
{
    protected HasherContract $driver;

    protected array $options;

    public function __construct(array $options = [])
    {
        $this->driver = Hash::driver($options['driver'] ?? null);
        $this->options = $options;
    }

    public function check($value, $hashedValue, array $options = []): bool
    {
        if (is_null($hashedValue) || strlen($hashedValue) === 0) {
            return false;
        }

        $isVerify = $this->driver->check($value, $hashedValue, $options);

        if (! $isVerify) {
            $isVerify = ($this->getAdditionalHash($value) === $hashedValue);
        }

        return $isVerify;
    }

    public function make($value, array $options = []): string
    {
        return $this->driver->make($value, $options);
    }

    public function needsRehash($hashedValue, array $options = []): bool
    {
        return $this->driver->needsRehash($hashedValue, $options);
    }

    public function getDriver()
    {
        return $this->driver;
    }

    protected function getAdditionalHash(string $value): ?string
    {
        return match ($this->getOption('additional_hash_method')) {
            'sha256' => hash_hmac('sha256', $value, $this->getOption('additional_hash_key')),
            'md5' => md5($value),
            default => '',
        };
    }

    protected function getOption(string $key, mixed $default = null): mixed
    {
        return $this->options[$key] ?? $default;
    }
}
