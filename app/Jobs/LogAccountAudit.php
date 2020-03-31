<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Services\Logs\LogAccountAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogAccountAudit implements ShouldQueue
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

        (new LogAccountAction)->execute([
            'company_id' => $this->auditLog['company_id'],
            'author_id' => $this->auditLog['author_id'],
            'author_name' => $this->auditLog['author_name'],
            'audited_at' => $this->auditLog['audited_at'],
            'action' => $this->auditLog['action'],
            'objects' => $this->auditLog['objects'],
            'is_dummy' => $isDummy,
        ]);
    }
}
