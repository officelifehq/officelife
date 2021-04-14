<?php

namespace App\Services\User;

use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword extends BaseService implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'password' => $this->passwordRules(true),
        ];
    }

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     */
    public function reset($user, array $input)
    {
        $this->validateRules($input);

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
