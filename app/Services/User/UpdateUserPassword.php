<?php

namespace App\Services\User;

use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword extends BaseService implements UpdatesUserPasswords
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
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(true),
        ];
    }

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     */
    public function update($user, array $input)
    {
        $this->validateRules($input);

        Validator::make($input, $this->rules())
            ->after(function ($validator) use ($user, $input) {
                if (! isset($input['current_password']) || ! Hash::check($input['current_password'], $user->password)) {
                    $validator->errors()->add('current_password', trans('auth.mismatch'));
                }
            })->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
