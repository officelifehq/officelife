<?php

namespace App\Services;

use Throwable;
use App\Jobs\ServiceQueue;

/**
 * This trait helps dispatch a QueuableService.
 */
trait DispatchableService
{
    final public function __construct()
    {
    }

    /**
     * Dispatch the service with the given arguments.
     *
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public static function dispatch(...$arguments)
    {
        /** @var QueuableService $service */
        $service = new static();
        $service->init(...$arguments);
        return ServiceQueue::dispatch($service);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     */
    public function failed(Throwable $exception): void
    {
    }
}
