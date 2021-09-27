<?php

namespace App\Services\User;

use Laravel\Fortify\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @param boolean $confirmed
     * @return array
     */
    protected function passwordRules($confirmed = false)
    {
        $passwordRules = new Password;

        if (config('auth.complex_password')) {
            $passwordRules = $passwordRules->length(7)->requireNumeric();
        } else {
            $passwordRules = $passwordRules->length(3);
        }

        return [
            'nullable',
            'string',
            $passwordRules,
            $confirmed ? 'confirmed' : '',
        ];
    }
}
