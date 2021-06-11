<?php

namespace Tests\Unit\Jobs;

use Throwable;
use App\Services\BaseService;
use App\Services\QueuableService;
use App\Services\DispatchableService;

class ServiceQueueTester extends BaseService implements QueuableService
{
    use DispatchableService;

    public ?array $data = null;
    public static bool $executed = false;
    public static bool $failed = false;

    /**
     * Initialize the service.
     *
     * @param array $data
     */
    public function init(array $data = []): self
    {
        $this->data = $data;
        self::$executed = false;
        self::$failed = false;

        return $this;
    }

    /**
     * Execute the service.
     */
    public function handle(): void
    {
        self::$executed = true;

        if ($this->data && $this->data['throw'] === true) {
            throw new \Exception();
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     */
    public function failed(Throwable $exception): void
    {
        self::$failed = true;
    }
}
