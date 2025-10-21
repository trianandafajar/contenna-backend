<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        $length = strlen($value);
        $hasDigit = preg_match('/\d/', $value);
        $hasUppercase = preg_match('/[A-Z]/', $value);
        $hasSymbol = preg_match('/[^a-zA-Z\d]/', $value);
        $hasLowercase = preg_match('/[a-z]/', $value);

        return $length >= 8 && $hasDigit && $hasUppercase && $hasSymbol && $hasLowercase;
    }

    public function message()
    {
        return 'The :attribute must be at least 8 characters long, contain at least one digit, one uppercase letter, and one symbol.';
    }
}
