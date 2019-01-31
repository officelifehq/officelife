<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;
use App\Services\Account\Account\LogAction;
use App\Services\User\Avatar\GenerateAvatar;
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
            'author_id' => 'required|integer|exists:users,id',
            'email' => 'required|email|string',
            'password' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'is_administrator' => 'required|boolean',
            'is_dummy' => 'nullable|boolean',
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

        $author = $this->validatePermissions($data['author_id'], 'hr');

        if (! $this->uniqueInAccount($data)) {
            throw new EmailAlreadyUsedException;
        }

        $user = $this->createUser($data);

        (new LogAction)->execute([
            'account_id' => $data['account_id'],
            'action' => 'user_created',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $user;
    }

    /**
     * Create the user.
     *
     * @param array $data
     * @return User
     */
    private function createUser(array $data) : User
    {
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateAvatar)->execute([
            'uuid' => $uuid,
            'size' => 200,
        ]);

        return User::create([
            'account_id' => $data['account_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $this->nullOrValue($data, 'first_name'),
            'last_name' => $this->nullOrValue($data, 'last_name'),
            'permission_level' => ($data['is_administrator'] ? config('homas.authorizations.administrator') : config('homas.authorizations.user')),
            'uuid' => $uuid,
            'avatar' => $avatar,
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
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
