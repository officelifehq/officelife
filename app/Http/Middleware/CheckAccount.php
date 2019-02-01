<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // surprisingly, this gets the object, not the id
        $account = $request->route()->parameter('account');

        if (auth()->user()->account_id == $account->id) {
            return $next($request);
        }

        abort(402);
    }
}
