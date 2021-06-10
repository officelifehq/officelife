<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Action;
use Illuminate\Queue\SerializesModels;
use App\Models\Company\ScheduledAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Adminland\Flow\Actions\ProcessActionCreateTask;

class ProcessScheduleAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The schedule instance instance.
     *
     * @var ScheduledAction
     */
    public ScheduledAction $scheduledAction;

    /**
     * Create a new job instance.
     *
     * @param ScheduledAction $scheduledAction
     */
    public function __construct(ScheduledAction $scheduledAction)
    {
        $this->scheduledAction = $scheduledAction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // execute the scheduled action
        if ($this->scheduledAction->action->type == Action::TYPE_CREATE_TASK) {
            (new ProcessActionCreateTask)->execute($this->scheduledAction);
        }
    }
}
