<?php

namespace App\Services\User;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        $passwordRules = new Password;
        if (config('auth.complex_password')) {
            $passwordRules = $passwordRules->requireUppercase()->requireNumeric()->requireSpecialCharacter();
        } else {
            $passwordRules = $passwordRules->length(3);
        }
        return [
            'required',
            'string',
            $passwordRules,
        ];
    }
}
