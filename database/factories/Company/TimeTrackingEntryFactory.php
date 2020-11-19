<?php

namespace Database\Factories\Company;

use Carbon\Carbon;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeTrackingEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TimeTrackingEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'timesheet_id' => Timesheet::factory()->create([
                'company_id' => $company->id,
            ]),
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'project_id' => Project::factory()->create([
                'company_id' => $company->id,
            ]),
            'duration' => 100,
            'happened_at' => Carbon::now(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
