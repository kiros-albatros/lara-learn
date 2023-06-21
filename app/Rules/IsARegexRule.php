<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsARegexRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Проверяем, является ли регулярным выражением
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            preg_match($value, '');

            return true;
        } catch (\ErrorException $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Строка не является правильным регулярным выражением';
    }
}
