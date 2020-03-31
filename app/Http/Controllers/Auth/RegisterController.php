<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\User\CreateAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\ThrottlesLogins;

class RegisterController extends Controller
{
    use ThrottlesLogins;

    /**
     * Show the register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return Inertia::render('Auth/Register');
    }

    /**
     * Store the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $data = [
            'email' => $email,
            'password' => $password,
        ];

        (new CreateAccount)->execute($data);

        Auth::attempt($data);

        return Redirect::route('home');
    }
}
