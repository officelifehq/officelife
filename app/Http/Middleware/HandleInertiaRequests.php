<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth' => fn () => [
                'user' => $request->user() ? [
                    'id' => $request->user()->id,
                    'first_name' => $request->user()->first_name,
                    'last_name' => $request->user()->last_name,
                    'email' => $request->user()->email,
                    'name' => $request->user()->name,
                    'show_help' => $request->user()->show_help,
                    'locale' => $request->user()->locale,
                ] : null,
                'company' => $request->user() && ! is_null(InstanceHelper::getLoggedCompany()) ? InstanceHelper::getLoggedCompany() : null,
                'employee' => $request->user() && ! is_null(InstanceHelper::getLoggedEmployee()) ? [
                    'id' => InstanceHelper::getLoggedEmployee()->id,
                    'first_name' => InstanceHelper::getLoggedEmployee()->first_name,
                    'last_name' => InstanceHelper::getLoggedEmployee()->last_name,
                    'name' => InstanceHelper::getLoggedEmployee()->name,
                    'permission_level' => InstanceHelper::getLoggedEmployee()->permission_level,
                    'display_welcome_message' => InstanceHelper::getLoggedEmployee()->display_welcome_message,
                    'user' => (! InstanceHelper::getLoggedEmployee()->user) ? null : [
                        'id' => InstanceHelper::getLoggedEmployee()->user_id,
                    ],
                ] : null,
            ],
            'demo_mode' => fn () => config('officelife.demo_mode'),
            'help_links' => fn () => config('officelife.help_links'),
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
            ],
            'errors' => fn () => $request->session()->get('errors')
                    ? $request->session()->get('errors')->getBag('default')->getMessages()
                    : [],
        ]);
    }
}
