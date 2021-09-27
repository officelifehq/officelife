<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProvisionDefaultAccountData;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\CreateCompany;

class CreateCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_company(): void
    {
        Queue::fake();

        $dwight = User::factory()->create([]);

        $request = [
            'author_id' => $dwight->id,
            'name' => 'Dunder Mifflin',
        ];

        $company = (new CreateCompany)->execute($request);

        $company->refresh();

        $michael = $dwight->getEmployeeObjectForCompany($company);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Dunder Mifflin',
        ]);

        $this->assertNotNull($company->code_to_join_company);

        $this->assertEquals(
            'dunder-mifflin',
            $company->slug
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'account_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'company_name' => 'Dunder Mifflin',
                ]);
        });

        Queue::assertPushed(ProvisionDefaultAccountData::class);

        // it has one employee
        $this->assertDatabaseHas('employees', [
            'company_id' => $company->id,
            'id' => $michael->id,
            'display_welcome_message' => true,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $dwight = User::factory()->create([]);

        $request = [
            'author_id' => $dwight->id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateCompany)->execute($request);
    }
}
