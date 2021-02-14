<?php

namespace App\Providers;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailMessaging;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        $this->registerInertia();

        VerifyEmail::toMailUsing(function ($user, $verificationUrl) {
            return EmailMessaging::verifyEmailMail($user, $verificationUrl);
        });

        if (App::runningInConsole()) {
            Command::macro('exec', function (string $message, string $commandline) {
                // @codeCoverageIgnoreStart
                /** @var \Illuminate\Console\Command */
                $command = $this;
                \App\Console\Commands\Helpers\Command::exec($command, $message, $commandline);
                // @codeCoverageIgnoreEnd
            });
            Command::macro('artisan', function (string $message, string $commandline, array $arguments = []) {
                /** @var \Illuminate\Console\Command */
                $command = $this;
                \App\Console\Commands\Helpers\Command::artisan($command, $message, $commandline, $arguments);
            });
        }
    }

    /**
     * Register any application services.
     */
    public function register()
    {
    }

    public function registerInertia(): void
    {
        Inertia::version(function () {
            return md5_file(public_path('mix-manifest.json'));
        });

        Inertia::share([
            'auth' => function () {
                return [
                    'user' => Auth::user() ? [
                        'id' => Auth::user()->id,
                        'first_name' => Auth::user()->first_name,
                        'last_name' => Auth::user()->last_name,
                        'email' => Auth::user()->email,
                        'name' => Auth::user()->name,
                        'show_help' => Auth::user()->show_help,
                    ] : null,
                    'company' => Auth::user() && ! is_null(InstanceHelper::getLoggedCompany()) ? InstanceHelper::getLoggedCompany() : null,
                    'employee' => Auth::user() && ! is_null(InstanceHelper::getLoggedEmployee()) ? [
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
                ];
            },
            'help_links' => function () {
                return config('officelife.help_links');
            },
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                ];
            },
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : [];
            },
        ]);
    }
}
