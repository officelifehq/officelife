<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\RenameCompany;

class RenameCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_renames_a_company_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function HR_user_cant_execute_the_service(): void
    {
        $michael = $this->createHR();

        $this->expectException(NotEnoughPermissionException::class);
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
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new RenameCompany)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $oldName = $michael->company->name;

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Pyramid',
        ];

        $company = (new RenameCompany)->execute($request);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Pyramid',
        ]);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $oldName) {
            return $job->auditLog['action'] === 'company_renamed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'old_name' => $oldName,
                    'new_name' => 'Pyramid',
                ]);
        });
    }
}
