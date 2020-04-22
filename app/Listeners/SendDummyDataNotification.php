<?php

namespace App\Listeners;

use App\Events\DummyDataGenerated;

class SendDummyDataNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DummyDataGenerated $event
     */
    public function handle(DummyDataGenerated $event)
    {
        //
    }
}
