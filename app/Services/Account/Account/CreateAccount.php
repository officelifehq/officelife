<?php

namespace App\Services\Account\Account;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Mail\ConfirmAccount;
use App\Services\BaseService;
use App\Models\Account\Account;
use App\Services\User\CreateUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\User\Avatar\GenerateAvatar;

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
            'email' => 'required|unique:users|email|string',
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

        $account = Account::create([]);

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
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateAvatar)->execute([
            'uuid' => $uuid,
            'size' => 200,
        ]);

        $user = User::create([
            'account_id' => $account->id,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'uuid' => $uuid,
            'avatar' => $avatar,
            'permission_level' => config('homas.authorizations.administrator'),
        ]);

        (new LogAction)->execute([
            'account_id' => $account->id,
            'action' => 'account_created',
            'objects' => json_encode([
                'author_id' => $user->id,
                'author_email' => $user->email,
            ]),
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
