<?php

namespace App\Listeners;

use App\Events\DummyDataGenerated;

class SendDummyDataNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DummyDataGenerated $event
     * @return void
     */
    public function handle(DummyDataGenerated $event)
    {
        //
    }
}
