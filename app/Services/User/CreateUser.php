<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Services\BaseService;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\EmailAlreadyUsedException;

class CreateUser extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|integer|exists:accounts,id',
            'email' => 'required|email|string',
            'password' => 'required|string|max:255',
        ];
    }

    /**
     * Create a user.
     *
     * @param array $data
     * @return User
     */
    public function execute(array $data) : User
    {
        $this->validate($data);

        if (! $this->uniqueInAccount($data)) {
            throw new EmailAlreadyUsedException;
        }

        return User::create([
            'account_id' => $data['account_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Make sure a user is unique per email address in this account.
     *
     * @param array $data
     * @return bool
     */
    private function uniqueInAccount(array $data)
    {
        $user = User::where('account_id', $data['account_id'])
            ->where('email', $data['email'])
            ->first();

        if ($user) {
            return false;
        }

        return true;
    }
}
