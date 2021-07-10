<?php

namespace App\Providers;

use App\Events\FileDeleted;
use App\Listeners\DeleteFileInStorage;
use Illuminate\Auth\Events\Registered;
use SocialiteProviders\Manager\SocialiteWasCalled;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        FileDeleted::class => [
            DeleteFileInStorage::class,
        ],
        SocialiteWasCalled::class => [
            'SocialiteProviders\\Azure\\AzureExtendSocialite@handle',
            'SocialiteProviders\\GitHub\\GitHubExtendSocialite@handle',
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
            'App\\Providers\\Auth\\MonicaExtendSocialite@handle',
            'SocialiteProviders\\LinkedIn\\LinkedInExtendSocialite@handle',
            'SocialiteProviders\\Slack\\SlackExtendSocialite@handle',
            'SocialiteProviders\\Saml2\\Saml2ExtendSocialite@handle',
            'SocialiteProviders\\Twitter\\TwitterExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
