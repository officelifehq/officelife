<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    /**
     * Shows the signin page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        return View::component('Login');
    }

    /**
     * Authenticate the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            return redirect('/home');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/home');
        }

        return response()->json([
            'errors' => '😳 Invalid credentials',
        ], 403);
    }

    /**
     * Logs out the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
