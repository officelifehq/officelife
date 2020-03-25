<?php

namespace Tests\Unit\Services\Company\Employee\Morale;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Morale;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Employee\Morale\LogMorale;
use App\Exceptions\MoraleAlreadyLoggedTodayException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogMoraleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_morale_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_logs_a_morale_as_hr(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_logs_a_morale_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function a_normal_user_cant_execute_service_for_another_normal_user(): void
    {
        $this->expectException(NotEnoughPermissionException::class);
        $michael = $this->createEmployee();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_doesnt_let_record_morale_if_one_has_already_been_submitted_today(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = factory(Employee::class)->create([]);
        factory(Morale::class)->create([
            'employee_id' => $michael->id,
            'created_at' => now(),
        ]);

        $request = [
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'company_id' => $michael->company_id,
            'emotion' => 1,
            'comment' => 'Michael is my idol',
        ];

        $this->expectException(MoraleAlreadyLoggedTodayException::class);
        (new LogMorale)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_company(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $michael = $this->createEmployee();
        $dwight = factory(Employee::class)->create([]);

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LogMorale)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $request = [
            'author_id' => $michael->id,
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'emotion' => 1,
            'comment' => 'Michael is my idol',
        ];

        $morale = (new LogMorale)->execute($request);

        $this->assertDatabaseHas('morale', [
            'id' => $morale->id,
            'employee_id' => $dwight->id,
            'emotion' => 1,
            'comment' => 'Michael is my idol',
            'is_dummy' => false,
        ]);

        $this->assertInstanceOf(
            Morale::class,
            $morale
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $morale, $dwight) {
            return $job->auditLog['action'] === 'employee_morale_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'morale_id' => $morale->id,
                    'emotion' => $morale->emotion,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $morale, $dwight) {
            return $job->auditLog['action'] === 'morale_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'morale_id' => $morale->id,
                    'emotion' => $morale->emotion,
                ]);
        });
    }
}
