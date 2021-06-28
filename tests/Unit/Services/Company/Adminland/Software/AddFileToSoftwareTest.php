<?php

namespace Tests\Unit\Services\Company\Adminland\Software;

use Tests\TestCase;
use App\Models\Company\File;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Software\AddFileToSoftware;

class AddFileToSoftwareTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_file_to_a_software_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $file, $software);
    }

    /** @test */
    public function it_adds_a_file_to_a_software_as_hr(): void
    {
        $michael = $this->createHR();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $file, $software);
    }

    /** @test */
    public function it_cant_add_a_file_to_a_software_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $file, $software);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AddFileToSoftware)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_file_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create();
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $file, $software);
    }

    /** @test */
    public function it_fails_if_the_project_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $software = Software::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $file, $software);
    }

    private function executeService(Employee $michael, File $file, Software $software): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'software_id' => $software->id,
            'file_id' => $file->id,
        ];

        $file = (new AddFileToSoftware)->execute($request);

        $this->assertDatabaseHas('file_software', [
            'software_id' => $software->id,
            'file_id' => $file->id,
        ]);

        $this->assertInstanceOf(
            File::class,
            $file
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $software, $file) {
            return $job->auditLog['action'] === 'file_added_to_software' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'software_id' => $software->id,
                    'software_name' => $software->name,
                    'name' => $file->name,
                ]);
        });
    }
}
