<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Services\Logs\LogEmployeeAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogEmployeeAudit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The audit log instance.
     *
     * @var array
     */
    public array $auditLog;

    /**
     * Create a new job instance.
     */
    public function __construct(array $auditLog)
    {
        $this->auditLog = $auditLog;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new LogEmployeeAction)->execute([
            'employee_id' => $this->auditLog['employee_id'],
            'author_id' => $this->auditLog['author_id'],
            'author_name' => $this->auditLog['author_name'],
            'audited_at' => $this->auditLog['audited_at'],
            'action' => $this->auditLog['action'],
            'objects' => $this->auditLog['objects'],
        ]);
    }
}
