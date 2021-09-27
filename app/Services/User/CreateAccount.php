<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Helpers\LocaleHelper;
use App\Services\BaseService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateAccount extends BaseService implements CreatesNewUsers
{
    use PasswordValidationRules;

    private User $user;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'locale' => [
                'nullable',
                'string',
                Rule::in(config('lang-detector.languages')),
            ],
        ];
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return User
     */
    public function create(array $input): User
    {
        return $this->execute($input);
    }

    /**
     * Create a user and send a confirmation email.
     *
     * @param array $data
     *
     * @return User
     */
    public function execute(array $data): User
    {
        $this->validateRules($data);

        $this->createUser($data);

        if (! config('mail.verify') || User::count() == 1) {
            // if it's the first user, we can skip the email verification
            $this->user->markEmailAsVerified();
        }

        return $this->user;
    }

    /**
     * Create the user.
     *
     * @param array $data
     */
    private function createUser(array $data): void
    {
        $uuid = Str::uuid()->toString();
        $locale = $this->valueOrNull($data, 'locale');
        if (! $locale) {
            $locale = LocaleHelper::getLocale();
        }

        $password = $this->valueOrNull($data, 'password');

        $this->user = User::create([
            'email' => $data['email'],
            'password' => $password != null ? Hash::make($password) : null,
            'first_name' => $this->valueOrNull($data, 'first_name'),
            'last_name' => $this->valueOrNull($data, 'last_name'),
            'middle_name' => $this->valueOrNull($data, 'middle_name'),
            'nickname' => $this->valueOrNull($data, 'nickname'),
            'uuid' => $uuid,
            'locale' => $locale,
        ]);
    }
}
