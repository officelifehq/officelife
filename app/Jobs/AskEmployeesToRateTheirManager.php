<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;

class AskEmployeesToRateTheirManager implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Employee $manager;

    /**
     * Create a new job instance.
     */
    public function __construct(Employee $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Ask every employee of the given manager to rate him.
     * First we need to create the Rate Your Manager Entry.
     * Next, for each employee of this manager, we need to create a Rate your
     * manager Answer, which will store the answer.
     */
    public function handle(): void
    {
        // create the RateYourManagerSurvey
        $entry = RateYourManagerSurvey::create([
            'manager_id' => $this->manager->id,
            'active' => true,
            'valid_until_at' => Carbon::now()->endOfDay()->addWeekdays(3),
        ]);

        $employees = $this->manager->directReports()->get();

        foreach ($employees as $employee) {
            $employee = $employee->directReport;

            if ($employee->locked) {
                continue;
            }

            RateYourManagerAnswer::create([
                'active' => true,
                'rate_your_manager_survey_id' => $entry->id,
                'employee_id' => $employee->id,
            ]);
        }
    }
}
