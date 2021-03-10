<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\AvatarHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Dashboard\DashboardHRViewHelper;

class DashboardHRViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_employees_without_managers_who_have_pending_timesheets(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        // michael will be direct report of dwight
        (new AssignManager)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ]);

        // michael has one unapproved timesheets
        Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => '2017-12-25 00:00:00',
            'status' => Timesheet::READY_TO_SUBMIT,
        ]);

        // dwight has one unapproved timesheets, but it shouldn't appear in the collection as he has a manager
        Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'started_at' => '2017-12-25 00:00:00',
            'status' => Timesheet::READY_TO_SUBMIT,
        ]);

        $array = DashboardHRViewHelper::employeesWithoutManagersWithPendingTimesheets($michael->company);

        $this->assertEquals(1, $array['employees']->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => AvatarHelper::getImage($michael),
                ],
            ],
            $array['employees']->toArray()
        );

        $this->assertEquals(
            env('APP_URL').'/'.$dwight->company_id.'/dashboard/hr/timesheets',
            $array['url_view_all']
        );
    }

    /** @test */
    public function it_gets_a_collection_of_statistics_about_timesheets(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $company = Company::factory()->create();

        // we'll have
        // - 2 timesheets in a ready to submit state
        // - 2 timesheets in a rejected state
        // - 2 timesheets in a accepted state
        Timesheet::factory()->count(2)->create([
            'company_id' => $company->id,
            'started_at' => '2017-12-25 00:00:00',
            'status' => Timesheet::READY_TO_SUBMIT,
        ]);
        Timesheet::factory()->count(2)->create([
            'company_id' => $company->id,
            'started_at' => '2017-12-25 00:00:00',
            'status' => Timesheet::REJECTED,
        ]);
        Timesheet::factory()->count(2)->create([
            'company_id' => $company->id,
            'started_at' => '2017-12-25 00:00:00',
            'status' => Timesheet::APPROVED,
        ]);

        $array = DashboardHRViewHelper::statisticsAboutTimesheets($company);

        $this->assertEquals(
            [
                'total' => 6,
                'rejected' => 2,
            ],
            $array
        );
    }
}
