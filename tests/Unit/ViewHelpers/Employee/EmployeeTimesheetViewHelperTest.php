<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\SQLHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectTask;
use App\Models\Company\TimeTrackingEntry;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeTimesheetViewHelper;

class EmployeeTimesheetViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_employee_timesheets(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $now = Carbon::now();

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
            'status' => Timesheet::APPROVED,
            'approver_id' => $michael->id,
            'approved_at' => $now,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheet->id,
            'employee_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'happened_at' => Carbon::now()->startOfWeek(),
            'duration' => 100,
        ]);

        // running the same query as the one from the controller
        $timesheets = DB::table(DB::raw('timesheets, employees, time_tracking_entries'))
            ->select(DB::raw('employees.id as employee_id, '.SQLHelper::concat('employees.first_name', 'employees.last_name').' as name, timesheets.started_at, timesheets.ended_at, timesheets.id as id, timesheets.status as status, sum(time_tracking_entries.duration) as duration'))
            ->whereRaw('timesheets.company_id = '.$michael->company->id)
            ->whereRaw('timesheets.employee_id = '.$michael->id)
            ->whereRaw('timesheets.employee_id = employees.id')
            ->whereRaw('time_tracking_entries.timesheet_id = timesheets.id')
            ->groupBy('timesheets.id')
            ->orderBy('timesheets.started_at')
            ->get();

        $array = EmployeeTimesheetViewHelper::timesheets($timesheets, $michael, $michael->company);

        $this->assertEquals(1, $array['entries']->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $timesheet->id,
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                    'duration' => '01 h 40',
                    'status' => Timesheet::APPROVED,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/'.$timesheet->id,
                ],
            ],
            $array['entries']->toArray()
        );

        $this->assertEquals(
            [
                'approved' => 1,
                'rejected' => 0,
            ],
            $array['statistics']
        );
    }

    /** @test */
    public function it_gets_a_collection_of_years_representing_all_the_years_the_employee_has_a_timesheet_for(): void
    {
        $michael = factory(Employee::class)->create([]);

        // logging worklogs
        Timesheet::factory()->create([
            'employee_id' => $michael->id,
            'started_at' => '2020-01-01 00:00:00',
        ]);
        Timesheet::factory()->create([
            'employee_id' => $michael->id,
            'started_at' => '1990-01-01 00:00:00',
        ]);

        $entries = $michael->timesheets()->orderBy('timesheets.started_at')->get();

        $collection = EmployeeTimesheetViewHelper::yearsWithTimesheets($entries, $michael, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'number' => 1990,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/1990',
                ],
                1 => [
                    'number' => 2020,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020',
                ],
            ],
            $collection->toArray()
        );

        $this->assertInstanceOf(
            Collection::class,
            $collection
        );
    }

    /** @test */
    public function it_gets_a_collection_of_months_representing_all_the_months_the_employee_has_been_working_from_home(): void
    {
        $michael = factory(Employee::class)->create([]);

        Timesheet::factory()->create([
            'employee_id' => $michael->id,
            'started_at' => '2020-01-01',
        ]);
        Timesheet::factory()->create([
            'employee_id' => $michael->id,
            'started_at' => '2020-02-01',
        ]);
        Timesheet::factory()->create([
            'employee_id' => $michael->id,
            'started_at' => '2020-03-01',
        ]);

        $entries = $michael->timesheets;

        $collection = EmployeeTimesheetViewHelper::monthsWithTimesheets($entries, $michael, $michael->company, 2020);

        $this->assertEquals(
            [
                0 => [
                    'month' => 1,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/1',
                    'occurences' => 1,
                    'translation' => 'January',
                ],
                1 => [
                    'month' => 2,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/2',
                    'occurences' => 1,
                    'translation' => 'February',
                ],
                2 => [
                    'month' => 3,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/3',
                    'occurences' => 1,
                    'translation' => 'March',
                ],
                3 => [
                    'month' => 4,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/4',
                    'occurences' => 0,
                    'translation' => 'April',
                ],
                4 => [
                    'month' => 5,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/5',
                    'occurences' => 0,
                    'translation' => 'May',
                ],
                5 => [
                    'month' => 6,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/6',
                    'occurences' => 0,
                    'translation' => 'June',
                ],
                6 => [
                    'month' => 7,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/7',
                    'occurences' => 0,
                    'translation' => 'July',
                ],
                7 => [
                    'month' => 8,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/8',
                    'occurences' => 0,
                    'translation' => 'August',
                ],
                8 => [
                    'month' => 9,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/9',
                    'occurences' => 0,
                    'translation' => 'September',
                ],
                9 => [
                    'month' => 10,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/10',
                    'occurences' => 0,
                    'translation' => 'October',
                ],
                10 => [
                    'month' => 11,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/11',
                    'occurences' => 0,
                    'translation' => 'November',
                ],
                11 => [
                    'month' => 12,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/timesheets/overview/2020/12',
                    'occurences' => 0,
                    'translation' => 'December',
                ],
            ],
            $collection->toArray()
        );

        $this->assertInstanceOf(
            Collection::class,
            $collection
        );
    }
}
