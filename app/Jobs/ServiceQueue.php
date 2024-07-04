<?php

namespace App\Jobs;

use App\Services\BaseService;
use App\Services\QueuableService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ServiceQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The service to queue.
     */
    public QueuableService $service;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(BaseService $service)
    {
        if (! $service instanceof QueuableService) {
            throw new \Exception('Service is not queuable');
        }
        $this->service = $service;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->service->handle();
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->service->failed($exception);
    }
}
