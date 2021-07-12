<?php

namespace App\Http\Middleware;

use Inertia\Inertia;
use App\Helpers\LocaleHelper;

/**
 * Used by Jetstream to share data.
 * We needed to override this custom middleware in order to make
 * sure we pass the right data to the view.
 */
class ShareInertiaData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        Inertia::share(array_filter([
            'jetstream' => function () use ($request) {
                return [
                    'flash' => $request->session()->get('flash', []),
                    'languages' => LocaleHelper::getLocaleList(),
                    'enableSignups' => config('officelife.enable_signups'),
                ];
            },
            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

                return [
                    'two_factor_enabled' => ! is_null($request->user()->two_factor_secret),
                ];
            },
            'errorBags' => function () use ($request) {
                return collect(optional($request->session()->get('errors'))->getBags() ?: [])->mapWithKeys(function ($bag, $key) {
                    return [$key => $bag->messages()];
                })->all();
            },
        ]));

        return $next($request);
    }
}
