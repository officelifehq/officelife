<?php

namespace App\Jobs;

use App\Models\Company\Employee;
use App\Services\Company\Adminland\Company\ProvisionDefaultAccountData as CompanyProvisionDefaultAccountData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProvisionDefaultAccountData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Employee instance.
     */
    public Employee $employee;

    /**
     * Create a new job instance.
     */
    public function __construct(Employee $author)
    {
        $this->employee = $author;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new CompanyProvisionDefaultAccountData)->execute([
            'company_id' => $this->employee->company_id,
            'author_id' => $this->employee->id,
        ]);
    }
}
