<?php

namespace App\Services\Account\Account;

use App\Models\User\User;
use App\Services\BaseService;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;

class CreateAccount extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subdomain' => 'required|string|max:255',
            'email' => 'required|email|string',
            'password' => 'required|string|max:255',
        ];
    }

    /**
     * Create an account.
     *
     * @param array $data
     * @return Account
     */
    public function execute(array $data) : Account
    {
        $this->validate($data);

        $account = Account::create([
            'subdomain' => $data['subdomain'],
        ]);

        $user = User::create([
            'account_id' => $account->id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_administrator' => true,
        ]);

        (new LogAction)->execute([
            'account_id' => $account->id,
            'action' => 'account_created',
            'objects' => json_encode('{"user": '.$user->id.'}'),
        ]);

        return $account;
    }
}
