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
    /**
     * Create a new service.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
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
        $service = new self(...$arguments);
        return ServiceQueue::dispatch($service);
    }

    /**
     * Dispatch the service with the given arguments on the sync queue.
     *
     * @param  mixed  ...$arguments
     * @return mixed
     */
    public static function dispatchSync(...$arguments): mixed
    {
        /** @var QueuableService $service */
        $service = new self(...$arguments);
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
