<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

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
use App\Services\Company\Employee\Holiday\DestroyTimeOff;

class DestroyTimeOffTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_time_off(): void
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = factory(Employee::class)->create([]);
        $holiday = DB::table('employee_planned_holidays')->insertGetId([
            'employee_id' => $michael->id,
            'planned_date' => '2018-10-10',
            'type' => 'holiday',
            'full' => true,
        ]);
        $holiday = EmployeePlannedHoliday::find($holiday);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_planned_holiday_id' => $holiday->id,
        ];

        (new DestroyTimeOff)->execute($request);

        $this->assertDatabaseMissing('employee_planned_holidays', [
            'id' => $holiday->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $holiday) {
            return $job->auditLog['action'] === 'time_off_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'planned_holiday_date' => $holiday->planned_date,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $holiday) {
            return $job->auditLog['action'] === 'time_off_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'planned_holiday_date' => $holiday->planned_date,
                ]);
        });
    }

    /** @test */
    public function it_destroys_a_time_off_as_hr(): void
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = factory(Employee::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'permission_level' => config('officelife.authorizations.hr'),
        ]);
        $holiday = DB::table('employee_planned_holidays')->insertGetId([
            'employee_id' => $michael->id,
            'planned_date' => '2018-10-10',
            'type' => 'holiday',
            'full' => true,
        ]);
        $holiday = EmployeePlannedHoliday::find($holiday);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $dwight->id,
            'employee_planned_holiday_id' => $holiday->id,
        ];

        (new DestroyTimeOff)->execute($request);

        $this->assertDatabaseMissing('employee_planned_holidays', [
            'id' => $holiday->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($dwight, $holiday) {
            return $job->auditLog['action'] === 'time_off_destroyed' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'planned_holiday_date' => $holiday->planned_date,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($dwight, $holiday) {
            return $job->auditLog['action'] === 'time_off_destroyed' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'planned_holiday_date' => $holiday->planned_date,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTimeOff)->execute($request);
    }
}
