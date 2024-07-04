<?php

namespace App\Jobs;

use App\Services\User\Preferences\UpdateDashboardView;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateDashboardPreference implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The preference instance.
     */
    public array $preference;

    /**
     * Create a new job instance.
     */
    public function __construct(array $preference)
    {
        $this->preference = $preference;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new UpdateDashboardView)->execute([
            'employee_id' => $this->preference['employee_id'],
            'company_id' => $this->preference['company_id'],
            'view' => $this->preference['view'],
        ]);
    }
}
