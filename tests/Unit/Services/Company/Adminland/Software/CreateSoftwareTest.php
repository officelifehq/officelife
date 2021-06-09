<?php

namespace Tests\Unit\Services\Company\Adminland\Hardware;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Software\CreateSoftware;

class CreateSoftwareTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_software_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_software_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateSoftware)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Android phone',
            'product_key' => '123',
            'seats' => '2',
        ];

        $software = (new CreateSoftware)->execute($request);

        $this->assertDatabaseHas('softwares', [
            'id' => $software->id,
            'company_id' => $michael->company_id,
            'name' => 'Android phone',
            'product_key' => '123',
            'seats' => '2',
        ]);

        $this->assertInstanceOf(
            Software::class,
            $software
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $software) {
            return $job->auditLog['action'] === 'software_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'software_id' => $software->id,
                    'software_name' => $software->name,
                ]);
        });
    }
}
