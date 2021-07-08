<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User\UserToken;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\One\User as OAuth1User;
use Laravel\Socialite\Two\User as OAuth2User;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class SocialiteCallbackController extends Controller
{
    /**
     * Handle socalite redirect.
     *
     * @param Request $request
     * @param string $driver
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(Request $request, string $driver): RedirectResponse
    {
        $this->checkProvider($driver);

        $redirect = Socialite::driver($driver)->redirect();

        return Redirect::guest($redirect->getTargetUrl());
    }

    /**
     * Handle socalite callback.
     *
     * @param Request $request
     * @param string $driver
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request, string $driver): RedirectResponse
    {
        if (($error = $request->input('error')) != '') {
            return Redirect::intended(route('home'))
                ->withErrors([
                    'error' => $error,
                    'error_description' => $request->input('error_description'),
                ]);
        }

        $this->checkProvider($driver);

        $socialite = Socialite::driver($driver)->user();

        $driverId = $socialite->getId();

        if ($userToken = UserToken::where([
            'driver_id' => $driverId,
            'driver' => $driver,
        ])->first()) {
            $user = $userToken->user;
        } else {
            $user = tap($this->getUser($socialite), function ($user) use ($driver, $driverId, $socialite) {
                $token = [
                    'driver' => $driver,
                    'driver_id' => $driverId,
                    'user_id' => $user->id,
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

                UserToken::create($token);
            });
        }

        Auth::login($user, true);

        return Redirect::intended(route('home'));
    }

    /**
     * Get authenticated user.
     *
     * @param SocialiteUser $socialite
     * @return User
     */
    private function getUser(SocialiteUser $socialite): User
    {
        if ($user = Auth::user()) {
            return $user;
        }

        // User doesn't exist
        $name = Str::of($socialite->getName())->split('/[\s]+/');
        $data = [
            'email' => $socialite->getEmail(),
            'first_name' => count($name) >= 0 ? $name[0] : '',
            'last_name' => count($name) > 0 ? $name[1] : '',
            'nickname' => $socialite->getNickname(),
        ];

        $user = app(CreateAccount::class)->execute($data);

        $user->email_verified_at = Carbon::now();
        $user->save();

        return $user;
    }

    /**
     * Check the driver is activated.
     */
    private function checkProvider(string $driver)
    {
        Validator::validate(['driver' => $driver], [
            'driver' => Rule::in(config('auth.socialite_providers')),
        ]);
    }
}
