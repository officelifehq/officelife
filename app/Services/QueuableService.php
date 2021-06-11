<?php

namespace App\Services;

use Throwable;

/**
 * Makes a BaseService queuable using the generic ServiceQueue job.
 */
interface QueuableService
{
    /**
     * Initialize the service.
     *
     * @param array $data
     */
    public function init(array $data): self;

    /**
     * Execute the service.
     */
    public function execute(): void;

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     */
    public function failed(Throwable $exception): void;
}
