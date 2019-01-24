<?php

namespace App\Services\Account;

use App\Services\BaseService;
use App\Models\Account\Account;
use App\Services\User\CreateUser;

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

        $request = [
            'account_id' => $account->id,
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        (new CreateUser)->execute($request);

        return $account;
    }
}
