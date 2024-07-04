<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use App\Models\User\UserToken;
use App\Services\User\CreateAccount;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\One\User as OAuth1User;
use Laravel\Socialite\Two\User as OAuth2User;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;

class SocialiteCallbackController extends Controller
{
    /**
     * Handle socalite login.
     */
    public function login(Request $request, string $driver): SymfonyRedirectResponse
    {
        $this->checkProvider($driver);

        $redirect = $request->input('redirect');
        if ($redirect && Str::of($redirect)->startsWith($request->getSchemeAndHttpHost())) {
            Redirect::setIntendedUrl($redirect);
        }

        return Socialite::driver($driver)->redirect();
    }

    /**
     * Handle socalite callback.
     */
    public function callback(Request $request, string $driver): RedirectResponse
    {
        try {
            if ($request->input('error') != '') {
                throw ValidationException::withMessages([
                    $request->input('error_description'),
                ]);
            }

            $this->checkProvider($driver);

            $user = $this->authenticateUser($driver, Socialite::driver($driver)->user());

            Auth::login($user, true);

            return Redirect::intended(route('home'));
        } catch (ValidationException $e) {
            throw $e->redirectTo(Redirect::intended(route('default'))->getTargetUrl());
        }
    }

    /**
     * Authenticate the user.
     *
     * @param  \Laravel\Socialite\Contracts\User  $socialite
     */
    private function authenticateUser(string $driver, $socialite): User
    {
        if ($userToken = UserToken::where([
            'driver_id' => $driverId = $socialite->getId(),
            'driver' => $driver,
        ])->first()) {
            // Association already exist

            $user = $userToken->user;

            if (($userId = Auth::id()) && $userId !== $user->id) {
                throw ValidationException::withMessages([
                    trans('auth.provider_already_used'),
                ]);
            }
        } else {
            // New association: create user or add token to existing user
            $user = tap($this->getUser($socialite), function ($user) use ($driver, $driverId, $socialite) {
                $this->createUserToken($user, $driver, $driverId, $socialite);
            });
        }

        return $user;
    }

    /**
     * Get authenticated user.
     */
    private function getUser(SocialiteUser $socialite): User
    {
        if ($user = Auth::user()) {
            return $user;
        }

        // User doesn't exist
        $name = Str::of($socialite->getName())->split('/[\s]+/', 2);
        $data = [
            'email' => $socialite->getEmail(),
            'first_name' => count($name) > 0 ? $name[0] : '',
            'last_name' => count($name) > 1 ? $name[1] : '',
            'nickname' => $socialite->getNickname(),
        ];

        $user = app(CreateAccount::class)->execute($data);

        $user->email_verified_at = Carbon::now();
        $user->save();

        return $user;
    }

    /**
     * Create the user token register.
     */
    private function createUserToken(User $user, string $driver, string $driverId, SocialiteUser $socialite): UserToken
    {
        $token = [
            'driver' => $driver,
            'driver_id' => $driverId,
            'user_id' => $user->id,
            'email' => $socialite->getEmail(),
        ];
        if ($socialite instanceof OAuth1User) {
            $token['token'] = $socialite->token;
            $token['token_secret'] = $socialite->tokenSecret;
            $token['format'] = 'oauth1';
        } elseif ($socialite instanceof OAuth2User) {
            $token['token'] = $socialite->token;
            $token['refresh_token'] = $socialite->refreshToken;
            $token['expires_in'] = $socialite->expiresIn;
            $token['format'] = 'oauth2';
        } else {
            throw new \Exception('authentication format not supported');
        }

        return UserToken::create($token);
    }

    /**
     * Check if the driver is activated.
     */
    private function checkProvider(string $driver): void
    {
        if (! collect(config('auth.login_providers'))->contains($driver)) {
            throw ValidationException::withMessages(['This provider does not exist']);
        }
    }
}
