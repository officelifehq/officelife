<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class RegisterController extends Controller
{
    use ThrottlesLogins;

    /**
     * Show the register page.
     *
     * @return mixed
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        if (! config('officelife.enable_signups')) {
            return redirect()->route('login');
        }

        return Inertia::render('Auth/Register', [
            'signInUrl' => route('login'),
        ]);
    }

    /**
     * Store the user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $user = (new CreateAccount)->execute($request->only([
            'email',
            'password',
        ]));

        if (! config('mail.verify') || User::count() == 1) {
            // if it's the first user, we can skip the email verification
            $user->markEmailAsVerified();
        } else {
            $user->sendEmailVerificationNotification();
        }

        /** @var \Illuminate\Contracts\Auth\StatefulGuard */
        $guard = Auth::guard();
        $guard->login($user);

        return Redirect::route('home');
    }
}
