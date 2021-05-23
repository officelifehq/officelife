<?php

namespace App\Providers;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use App\Notifications\EmailMessaging;
use Illuminate\Support\Facades\Schema;
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
}
