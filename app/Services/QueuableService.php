<?php

namespace App\Services;

use Throwable;

/**
 * Makes a BaseService queuable using the generic ServiceQueue job.
 */
interface QueuableService
{
    /**
     * Execute the service.
     */
    public function handle(): void;

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     */
    public function failed(Throwable $exception): void;
}
