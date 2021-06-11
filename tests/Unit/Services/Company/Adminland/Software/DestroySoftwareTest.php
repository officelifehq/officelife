<?php

namespace Tests\Unit\Services\Company\Adminland\Software;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Software\DestroySoftware;

class DestroySoftwareTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_software_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_destroys_a_software_as_hr(): void
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
        (new DestroySoftware)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $software = Software::create([
            'company_id' => $michael->company_id,
            'name' => 'office',
            'product_key' => '456',
            'seats' => '5',
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'software_id' => $software->id,
        ];

        (new DestroySoftware)->execute($request);

        $this->assertDatabaseMissing('softwares', [
            'id' => $software->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $software) {
            return $job->auditLog['action'] === 'software_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'software_id' => $software->id,
                    'software_name' => $software->name,
                ]);
        });
    }
}
