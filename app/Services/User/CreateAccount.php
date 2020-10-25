<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class CreateAccount extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|alpha_dash|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
        ];
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

        $user = $this->createUser($data);

        return $user;
    }

    /**
     * Create the user.
     *
     * @param array $data
     *
     * @return User
     */
    private function createUser(array $data): User
    {
        $uuid = Str::uuid()->toString();

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $this->valueOrNull($data, 'first_name'),
            'last_name' => $this->valueOrNull($data, 'last_name'),
            'middle_name' => $this->valueOrNull($data, 'middle_name'),
            'nickname' => $this->valueOrNull($data, 'nickname'),
            'uuid' => $uuid,
        ]);

        return $user;
    }
}
