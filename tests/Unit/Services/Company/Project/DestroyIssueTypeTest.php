<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\IssueType;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Project\DestroyIssueType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyIssueTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_the_issue_type_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $type = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $type);
    }

    /** @test */
    public function it_deletes_the_issue_type_as_hr(): void
    {
        $michael = $this->createHR();
        $type = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $type);
    }

    /** @test */
    public function it_cant_delete_the_issue_type_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $type = IssueType::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $type);
    }

    /** @test */
    public function it_fails_if_issue_type_is_not_part_of_the_company(): void
    {
        $michael = $this->createHR();
        $type = IssueType::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $type);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create();

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new DestroyIssueType)->execute($request);
    }

    private function executeService(Employee $michael, IssueType $type): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'issue_type_id' => $type->id,
            'name' => 'Update',
        ];

        (new DestroyIssueType)->execute($request);

        $this->assertDatabaseMissing('issue_types', [
            'id' => $type->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $type) {
            return $job->auditLog['action'] === 'issue_type_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'issue_type_name' => $type->name,
                ]);
        });
    }
}
