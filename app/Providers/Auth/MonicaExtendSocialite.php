<?php

namespace App\Providers\Auth;

use SocialiteProviders\LaravelPassport\Provider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class MonicaExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('monica', Provider::class);
    }
}
