<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Account\Account\LogAction;

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
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            (new LogAction)->execute([
                'account_id' => auth()->user->account->id,
                'action' => 'logged_in',
                'objects' => json_encode([
                    'author_id' => auth()->user()->id,
                ]),
            ]);

            return redirect()->intended('dashboard');
        }

        return redirect('login')
                ->withErrors('Invalid credentials')
                ->withInput();
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
