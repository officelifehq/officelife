<?php

namespace App\Services\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use App\Mail\ConfirmAccount;
use App\Services\BaseService;
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
            'email' => 'required|unique:users,email|email|max:255',
            'password' => 'required|alpha_dash|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
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

        $user = $this->createUser($data);

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

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $this->nullOrValue($data, 'first_name'),
            'last_name' => $this->nullOrValue($data, 'last_name'),
            'middle_name' => $this->nullOrValue($data, 'middle_name'),
            'nickname' => $this->nullOrValue($data, 'nickname'),
            'uuid' => $uuid,
            'avatar' => $avatar,
        ]);

        $user = $this->generateConfirmationLink($user);

        $this->scheduleConfirmationEmail($user);

        return $user;
    }

    /**
     * Generate a confirmation link for the user.
     *
     * @param User $user
     * @return User
     */
    private function generateConfirmationLink($user) : User
    {
        $user->verification_link = Str::uuid()->toString();
        $user->save();

        return $user;
    }

    /**
     * Schedule a confirmation email to be sent.
     *
     * @param User $user
     * @return void
     */
    private function scheduleConfirmationEmail(User $user)
    {
        Mail::to($user->email)
            ->queue(new ConfirmAccount($user));
    }
}
