<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Inertia\Response
     */
    public function showLoginForm(): \Inertia\Response
    {
        return Inertia::render('Auth/Login', [
            'registerUrl' => route('signup'),
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function authenticated(Request $request, $user)
    {
        $path = $request->session()->pull('url.intended', route('home'));

        return $request->wantsJson()
            ? Response::json(['success' => true,'redirect' => $path])
            : Redirect::intended($path);
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Validation\ValidationException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return $request->wantsJson()
            ? Response::json(['data' => [trans('auth.login_invalid_credentials')]], 401)
            : Redirect::back()->with('message', trans('auth.login_invalid_credentials'));
    }
}
