<?php

namespace Tests\Unit\Jobs;

use App\Services\BaseService;
use App\Services\DispatchableService;
use App\Services\QueuableService;
use Throwable;

class ServiceQueueTester extends BaseService implements QueuableService
{
    use DispatchableService;

    public ?array $data = null;

    public static bool $executed = false;

    public static bool $failed = false;

    public bool $object;

    /**
     * Initialize the service.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
        self::$executed = false;
        self::$failed = false;
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
     */
    public function failed(Throwable $exception): void
    {
        self::$failed = true;

        if (isset($this->obj)) {
            // variable can be touch
        }
    }
}
