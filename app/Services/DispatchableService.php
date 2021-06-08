<?php

namespace App\Services;

use App\Jobs\ServiceQueue;

trait DispatchableService
{
    /**
     * Dispatch the service with the given arguments.
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public static function dispatch()
    {
        /** @var QueuableService $service */
        $service = new static();
        $service->init(...func_get_args());
        return ServiceQueue::dispatch($service);
    }
}
