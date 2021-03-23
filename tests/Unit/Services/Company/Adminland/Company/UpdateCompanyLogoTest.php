<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\RenameCompany;
use App\Services\Company\Adminland\Company\UpdateCompanyLogo;

class UpdateCompanyLogoTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_changes_the_logo_as_administrator(): void
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

        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'file_id' => $file->id,
        ];

        $file = (new UpdateCompanyLogo)->execute($request);

        $this->assertDatabaseHas('companies', [
            'id' => $michael->company->id,
            'logo_file_id' => $file->id,
        ]);

        $this->assertInstanceOf(
            File::class,
            $file
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'company_logo_changed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([]);
        });
    }
}
