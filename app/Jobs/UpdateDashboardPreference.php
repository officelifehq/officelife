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
    public $preference;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $preference)
    {
        $this->preference = $preference;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new UpdateDashboardView)->execute([
            'user_id' => $this->preference['user_id'],
            'company_id' => $this->preference['company_id'],
            'view' => $this->preference['view'],
        ]);
    }
}
