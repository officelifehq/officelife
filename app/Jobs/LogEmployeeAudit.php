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
    public $auditLog;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $auditLog)
    {
        $this->auditLog = $auditLog;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $isDummy = true;
        if (empty($this->auditLog['is_dummy'])) {
            $isDummy = false;
        }

        (new LogEmployeeAction)->execute([
            'company_id' => $this->auditLog['company_id'],
            'employee_id' => $this->auditLog['employee_id'],
            'action' => $this->auditLog['action'],
            'objects' => $this->auditLog['objects'],
            'is_dummy' => $isDummy,
        ]);
    }
}
