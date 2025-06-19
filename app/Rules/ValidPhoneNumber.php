<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Basic international phone number validation
        if (!preg_match('/^[\+]?[1-9][\d]{0,15}$/', $value)) {
            $fail('The :attribute must be a valid phone number.');
        }

        // Additional validation logic if needed
        if (strlen($value) < 10) {
            $fail('The :attribute must be at least 10 digits long.');
        }
    }
}
