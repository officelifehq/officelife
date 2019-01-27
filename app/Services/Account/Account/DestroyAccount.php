<?php

namespace App\Services\Account\Account;

use App\Services\BaseService;
use App\Models\Account\Account;

class DestroyAccount extends BaseService
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
            'author_id' => 'nullable|integer|exists:users,id',
        ];
    }

    /**
     * Destroy an account.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data)
    {
        $this->validate($data);

        $this->validatePermissions($data['author_id'], 'administrator');

        $account = Account::find($data['account_id']);

        $account->delete();

        return true;
    }
}
