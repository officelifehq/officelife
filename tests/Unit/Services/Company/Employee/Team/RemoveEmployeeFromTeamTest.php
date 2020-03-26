<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;

class RemoveEmployeeFromTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_from_a_team_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_removes_an_employee_from_a_team_as_hr(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        DB::table('employee_team')->insert([
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'team_id' => $sales->id,
        ];

        $this->expectException(ValidationException::class);
        (new RemoveEmployeeFromTeam)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $sales = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        DB::table('employee_team')->insert([
            'employee_id' => $dwight->id,
            'team_id' => $sales->id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'team_id' => $sales->id,
        ];

        $dwight = (new RemoveEmployeeFromTeam)->execute($request);

        $this->assertDatabaseMissing('employee_team', [
            'employee_id' => $dwight->id,
            'team_id' => $sales->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight, $sales) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $dwight, $sales) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $dwight, $sales) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($sales, $dwight) {
            return $job->notification['action'] === 'employee_removed_from_team' &&
                $job->notification['employee_id'] === $dwight->id &&
                $job->notification['objects'] === json_encode([
                    'team_name' => $sales->name,
                ]);
        });
    }
}
