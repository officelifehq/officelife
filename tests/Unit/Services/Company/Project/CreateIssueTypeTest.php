<?php

namespace Tests\Unit\Services\Company\Project;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\IssueType;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Services\Company\Project\CreateIssueType;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateIssueTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_an_issue_type_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_an_issue_type_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_cant_create_an_issue_type_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new CreateIssueType)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'name',
            'icon_hex_color' => '123',
        ];

        $type = (new CreateIssueType)->execute($request);

        $this->assertDatabaseHas('issue_types', [
            'id' => $type->id,
            'name' => 'name',
        ]);

        $this->assertInstanceOf(
            IssueType::class,
            $type
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $type) {
            return $job->auditLog['action'] === 'issue_type_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'issue_type_id' => $type->id,
                    'issue_type_name' => $type->name,
                ]);
        });
    }
}
