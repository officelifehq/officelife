<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Account\Account\CreateAccount;

class RegisterController extends Controller
{
    /**
     * Show the register page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Send a confirmation email to confirm the email address.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = [
            'subdomain' => $request->get('subdomain'),
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        ];

        $account = (new CreateAccount)->execute($request);

        return view('auth.register');
    }
}
