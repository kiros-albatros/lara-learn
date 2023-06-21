<?php

namespace App\Http;

use Illuminate\Http\Request as LaravelRequest;

class Request extends LaravelRequest
{
    /**
     * Получить значение, приведенное к строковому или default, если не задано
     * Нужно, чтобы быстро передавать данные в строго типизированные методы без генерации системных ошибок
     *
     * @param string $key
     * @param ?string $default
     *
     * @return ?string
     */
    public function stringOrNull(string $key, ?string $default = null): ?string
    {
        $value = $this->input($key);

        return ($value !== null and ! is_array($value)) ? (string)$value : $default;
    }

    /**
     * Получить значение, приведенное к целочисленному типу или default, если не задано
     * Нужно, чтобы быстро передавать данные в строго типизированные методы без генерации системных ошибок
     *
     * @param string $key
     * @param ?int $default
     *
     * @return ?int
     */
    public function intOrNull(string $key, ?int $default = null): ?int
    {
        $value = $this->input($key);

        return ($value !== null and ! is_array($value)) ? (int)$value : $default;
    }

    /**
     * Получить значение, приведенное к типу float или default, если не задано
     * Нужно, чтобы быстро передавать данные в строго типизированные методы без генерации системных ошибок
     *
     * @param string $key
     * @param ?float $default
     *
     * @return ?float
     */
    public function floatOrNull(string $key, ?float $default = null): ?float
    {
        $value = $this->input($key);

        return ($value !== null and ! is_array($value)) ? (float)$value : $default;
    }

    /**
     * Получить значение, приведенное к булевому типу или default, если не задано
     * Нужно, чтобы быстро передавать данные в строго типизированные методы без генерации системных ошибок
     *
     * @param string $key
     *
     * @return ?bool
     */
    public function boolOrNull(string $key, ?bool $default = null): ?bool
    {
        $value = $this->input($key);

        return ($value !== null and ! is_array($value)) ? $this->boolean($key) : $default;
    }

    /**
     * Получить значение, приведенное к массиву или default, если не задано
     * Нужно, чтобы быстро передавать данные в строго типизированные методы без генерации системных ошибок
     *
     * @param string $key
     *
     * @return ?array
     */
    public function arrayOrNull(string $key, ?array $default = null): ?array
    {
        $value = $this->input($key);

        return ($value !== null and is_array($value)) ? $value : $default;
    }
}
