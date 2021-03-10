<?php

namespace Tests\Unit\Services\Company\Adminland\CompanyPTOPolicy;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\CompanyPTOPolicy\UpdateCompanyPTOPolicy;

class UpdateCompanyPTOPolicyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_company_pto_policy_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_updates_a_company_pto_policy_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UpdateCompanyPTOPolicy)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_company_pto_policy_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $ptoPolicy = CompanyPTOPolicy::factory()->create([]);
        $calendarA = CompanyCalendar::factory()->create([
            'company_pto_policy_id' => $ptoPolicy->id,
            'day' => '2010-01-01',
        ]);
        $calendarB = CompanyCalendar::factory()->create([
            'company_pto_policy_id' => $ptoPolicy->id,
            'day' => '2010-01-02',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'company_pto_policy_id' => $ptoPolicy->id,
            'days_to_toggle' => [[
                'id' => $calendarA->id,
                'is_worked' => false,
            ], [
                'id' => $calendarB->id,
                'is_worked' => false,
            ]],
            'default_amount_of_allowed_holidays' => 100,
            'default_amount_of_sick_days' => 100,
            'default_amount_of_pto_days' => 100,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateCompanyPTOPolicy)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();
        Carbon::setTestNow(Carbon::create(2019, 1, 1));

        $ptoPolicy = CompanyPTOPolicy::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $calendarA = CompanyCalendar::factory()->create([
            'company_pto_policy_id' => $ptoPolicy->id,
            'day' => '2010-01-01',
        ]);
        $calendarB = CompanyCalendar::factory()->create([
            'company_pto_policy_id' => $ptoPolicy->id,
            'day' => '2010-01-02',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'company_pto_policy_id' => $ptoPolicy->id,
            'days_to_toggle' => [[
                'id' => $calendarA->id,
                'is_worked' => false,
            ], [
                'id' => $calendarB->id,
                'is_worked' => false,
            ]],
            'default_amount_of_allowed_holidays' => 100,
            'default_amount_of_sick_days' => 100,
            'default_amount_of_pto_days' => 100,
        ];

        $ptoPolicy = (new UpdateCompanyPTOPolicy)->execute($request);

        $this->assertDatabaseHas('company_pto_policies', [
            'id' => $ptoPolicy->id,
            'company_id' => $michael->company_id,
            'total_worked_days' => 248,
            'default_amount_of_allowed_holidays' => 100,
            'default_amount_of_sick_days' => 100,
            'default_amount_of_pto_days' => 100,
        ]);

        $this->assertDatabaseHas('company_calendars', [
            'id' => $calendarA->id,
            'is_worked' => 0,
        ]);

        $this->assertDatabaseHas('company_calendars', [
            'id' => $calendarB->id,
            'is_worked' => 0,
        ]);

        $this->assertInstanceOf(
            CompanyPTOPolicy::class,
            $ptoPolicy
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $ptoPolicy) {
            return $job->auditLog['action'] === 'company_pto_policy_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_pto_policy_id' => $ptoPolicy->id,
                    'company_pto_policy_year' => $ptoPolicy->year,
                ]);
        });
    }
}
