<?php

namespace Tests\Unit\Services\Company\Employee\Holiday;

use Exception;
use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeePlannedHoliday;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Holiday\CreateTimeOff;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class CreateTimeOffTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_new_time_off(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = factory(Employee::class)->create([]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        $request = [
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'type' => 'holiday',
            'full' => true,
        ];

        $holiday = (new CreateTimeOff)->execute($request);

        $this->assertDatabaseHas('employee_planned_holidays', [
            'id' => $holiday->id,
            'employee_id' => $michael->id,
            'planned_date' => '2018-10-10 00:00:00',
            'type' => 'holiday',
            'full' => true,
        ]);

        $this->assertInstanceOf(
            EmployeePlannedHoliday::class,
            $holiday
        );
    }

    /** @test */
    public function it_logs_a_new_time_off_as_a_hr_rep(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = factory(Employee::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'permission_level' => config('kakene.authorizations.hr'),
        ]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];
        (new CreateCompanyPTOPolicy)->execute($request);

        $request = [
            'author_id' => $dwight->id,
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'type' => 'holiday',
            'full' => true,
        ];

        Queue::fake();
        $holiday = (new CreateTimeOff)->execute($request);

        $this->assertDatabaseHas('employee_planned_holidays', [
            'id' => $holiday->id,
            'employee_id' => $michael->id,
            'planned_date' => '2018-10-10 00:00:00',
            'type' => 'holiday',
            'full' => true,
        ]);

        $this->assertInstanceOf(
            EmployeePlannedHoliday::class,
            $holiday
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($dwight, $holiday) {
            return $job->auditLog['action'] === 'time_off_created' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'planned_holiday_id' => $holiday->id,
                    'planned_holiday_date' => $holiday->planned_date,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($dwight, $holiday) {
            return $job->auditLog['action'] === 'time_off_created' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'planned_holiday_id' => $holiday->id,
                    'planned_holiday_date' => $holiday->planned_date,
                ]);
        });
    }

    /** @test */
    public function it_cant_log_a_time_off_as_the_planned_date_is_already_taken_as_holiday(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = factory(Employee::class)->create([]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];
        (new CreateCompanyPTOPolicy)->execute($request);

        // let's choose a sunday as the planned date
        $request = [
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-01-07',
            'type' => 'holiday',
            'full' => true,
        ];

        $this->expectException(Exception::class);
        (new CreateTimeOff)->execute($request);
    }

    /** @test */
    public function it_cant_log_a_time_off_as_the_planned_date_is_already_taken_as_day_off_in_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = factory(Employee::class)->create([]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];
        (new CreateCompanyPTOPolicy)->execute($request);

        // let's take a day that is already taken as a day off
        DB::table('employee_planned_holidays')->insert([
            'employee_id' => $michael->id,
            'planned_date' => '2018-10-10 00:00:00',
            'type' => 'holiday',
            'full' => true,
        ]);

        $request = [
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'type' => 'holiday',
            'full' => true,
        ];

        $this->expectException(Exception::class);
        (new CreateTimeOff)->execute($request);
    }

    /** @test */
    public function it_logs_a_half_time_off_if_the_planned_date_was_already_taken_but_it_was_only_a_half_day(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = factory(Employee::class)->create([]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        // let's take a day that is already taken as a day off
        DB::table('employee_planned_holidays')->insertGetId([
            'employee_id' => $michael->id,
            'planned_date' => '2018-10-10 00:00:00',
            'type' => 'holiday',
            'full' => false,
        ]);

        $request = [
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'type' => 'holiday',
            'full' => false,
        ];

        $holiday = (new CreateTimeOff)->execute($request);

        // there should be two entries in the database
        $count = DB::table('employee_planned_holidays')->where('planned_date', '2018-10-10 00:00:00')
            ->count();

        $this->assertEquals(
            2,
            $count
        );

        $this->assertInstanceOf(
            EmployeePlannedHoliday::class,
            $holiday
        );

        // we now try to add another half day but it should reject it
        $request = [
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'type' => 'holiday',
            'full' => false,
        ];

        $this->expectException(Exception::class);
        $holiday = (new CreateTimeOff)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTimeOff)->execute($request);
    }
}
