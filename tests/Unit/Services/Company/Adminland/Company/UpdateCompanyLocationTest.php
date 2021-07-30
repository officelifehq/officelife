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
use App\Services\Company\Adminland\Company\UpdateCompanyCurrency;
use App\Services\Company\Adminland\Company\UpdateCompanyLocation;

class UpdateCompanyLocationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_company_location_as_administrator(): void
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
        (new UpdateCompanyCurrency)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'location' => 'Montreal',
        ];

        $company = (new UpdateCompanyLocation)->execute($request);

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'location' => 'Montreal',
        ]);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'company_location_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'location' => 'Montreal',
                ]);
        });
    }
}
