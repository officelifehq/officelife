<?php

namespace App\Jobs;

use App\Services\Company\Employee\Notification\AddNotificationInUIForEmployee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyEmployee implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The notification instance.
     */
    public array $notification;

    /**
     * Create a new job instance.
     */
    public function __construct(array $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new AddNotificationInUIForEmployee)->execute([
            'employee_id' => $this->notification['employee_id'],
            'action' => $this->notification['action'],
            'objects' => $this->notification['objects'],
        ]);
    }
}
