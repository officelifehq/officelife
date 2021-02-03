<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany as EmployeeAddEmployeeToCompany;

class AddEmployeeToCompany implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array
     */
    public array $data;

    /**
     * Create a new job instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new EmployeeAddEmployeeToCompany)->execute([
            'company_id' => $this->data['company_id'],
            'author_id' => $this->data['author_id'],
            'email' => $this->data['email'],
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'permission_level' => $this->data['permission_level'],
            'send_invitation' => $this->data['send_invitation'],
        ]);
    }
}
