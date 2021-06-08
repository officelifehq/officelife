<?php

namespace App\Services;

use Throwable;

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
