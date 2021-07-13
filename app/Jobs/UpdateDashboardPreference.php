<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\User\Preferences\UpdateDashboardView;

class UpdateDashboardPreference implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The preference instance.
     *
     * @var array
     */
    public array $preference;

    /**
     * Create a new job instance.
     *
     * @param array $preference
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
