<?php

namespace App\Services;

use Throwable;
use App\Jobs\ServiceQueue;
use Illuminate\Foundation\Bus\PendingDispatch;

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
     * @param  mixed  ...$arguments
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public static function dispatch(...$arguments): PendingDispatch
    {
        /** @var QueuableService $service */
        $service = new static();
        $service->init(...$arguments);
        return ServiceQueue::dispatch($service);
    }

    /**
     * Dispatch the service with the given arguments.
     *
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function dispatchSync(...$arguments): mixed
    {
        /** @var QueuableService $service */
        $service = new static();
        $service->init(...$arguments);
        return ServiceQueue::dispatchSync($service);
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
