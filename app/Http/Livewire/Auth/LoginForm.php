<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginForm extends Component
{
    public $email;
    public $password;

    public function submit()
    {
        if (Auth::check()) {
            //return Redirect::route('home');
        }

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {
            return Redirect::route('home');
        }

        return Redirect::route('login')
            ->withErrors(trans('auth.login_invalid_credentials'));
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
