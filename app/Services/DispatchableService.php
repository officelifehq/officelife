<?php

namespace App\Services;

use App\Jobs\ServiceQueue;
use Illuminate\Foundation\Bus\PendingDispatch;
use Throwable;

/**
 * This trait helps dispatch a QueuableService.
 */
trait DispatchableService
{
    /**
     * Create a new service.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Dispatch the service with the given arguments.
     *
     * @param  mixed  ...$arguments
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
     */
    public static function dispatchSync(...$arguments): mixed
    {
        /** @var QueuableService $service */
        $service = new self(...$arguments);

        return ServiceQueue::dispatchSync($service);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void {}
}
