<?php

namespace App\Services\Account\Account;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Mail\ConfirmAccount;
use App\Services\BaseService;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
            'subdomain' => 'required|string|max:255|unique:accounts|alpha',
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

        $account = $this->generateConfirmationLink($account);

        $user = $this->createUser($account, $data);

        $this->scheduleConfirmationEmail($user, $account);

        return $account;
    }

    /**
     * Create the user.
     *
     * @param Account $account
     * @param array $data
     * @return User
     */
    private function createUser(Account $account, array $data) : User
    {
        $user = User::create([
            'account_id' => $account->id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'permission_level' => config('homas.authorizations.administrator'),
        ]);

        (new LogAction)->execute([
            'account_id' => $account->id,
            'action' => 'account_created',
            'objects' => json_encode('{"user": '.$user->id.'}'),
        ]);

        return $user;
    }

    /**
     * Generate a confirmation link for the account.
     *
     * @param Account $account
     * @return Account
     */
    private function generateConfirmationLink($account) : Account
    {
        $account->confirmation_link = Str::uuid()->toString();
        $account->save();

        return $account;
    }

    /**
     * Schedule a confirmation email to be sent.
     *
     * @param User $user
     * @param Account $account
     * @return void
     */
    private function scheduleConfirmationEmail(User $user, Account $account)
    {
        Mail::to($user->email)
            ->queue(new ConfirmAccount($account));
    }
}
