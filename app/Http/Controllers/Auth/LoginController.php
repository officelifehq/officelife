<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * Shows the login page.
     *
     * @return mixed
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return Inertia::render('Auth/Login', [
            'registerUrl' => route('signup'),
        ]);
    }

    /**
     * Authenticate the user.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            return Redirect::route('home');
        }

        $email = $request->input('email');
        $password = $request->input('password');
        $password = trim(preg_replace('/\t/', '', $password));
        $credentials = [
            'email' => $email,
            'password' => $password,
        ];

        if (Auth::attempt($credentials)) {
            return Redirect::route('home');
        }

        return response()->json([
            'data' => [
                trans('auth.login_invalid_credentials'),
            ],
        ], 500);
    }

    /**
     * Logs out the user.
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        Auth::logout();

        return Redirect::route('login');
    }
}
