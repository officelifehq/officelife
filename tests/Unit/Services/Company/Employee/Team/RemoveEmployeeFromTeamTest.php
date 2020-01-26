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
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;

class RemoveEmployeeFromTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_an_employee_from_a_team(): void
    {
        Queue::fake();

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
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ];

        $michael = (new RemoveEmployeeFromTeam)->execute($request);

        $this->assertDatabaseMissing('employee_team', [
            'employee_id' => $michael->id,
            'team_id' => $sales->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $sales
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $sales) {
            return $job->auditLog['action'] === 'employee_removed_from_team' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'team_id' => $sales->id,
                    'team_name' => $sales->name,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($sales, $michael) {
            return $job->notification['action'] === 'employee_removed_from_team' &&
                $job->notification['employee_id'] === $michael->id &&
                $job->notification['objects'] === json_encode([
                    'team_name' => $sales->name,
                ]);
        });
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
}
