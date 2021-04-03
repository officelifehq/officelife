<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Company\RenameCompany;
use App\Services\Company\Adminland\Company\DestroyCompany;

class DestroyCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_company_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $company = $michael->company;
        $this->executeService($michael, $company);
    }

    /** @test */
    public function HR_user_cant_execute_the_service(): void
    {
        $michael = $this->createHR();
        $company = $michael->company;

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $company);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $company = $michael->company;

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $company);
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

    /** @test */
    public function it_fails_to_destroy_the_company_if_administrator_is_not_linked_to_company(): void
    {
        $michael = $this->createAdministrator();
        $company = Company::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $company);
    }

    private function executeService(Employee $michael, Company $company): void
    {
        Queue::fake();

        $request = [
            'company_id' => $company->id,
            'author_id' => $michael->id,
        ];

        (new DestroyCompany)->execute($request);

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }
}
