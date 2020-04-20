<?php

namespace Tests\Unit\Services\Company\Adminland\Hardware;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Hardware\CreateHardware;

class CreateHardwareTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_harware_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_harware_as_hr(): void
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
        (new CreateHardware)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'Android phone',
            'serial_number' => '123',
        ];

        $hardware = (new CreateHardware)->execute($request);

        $this->assertDatabaseHas('hardware', [
            'id' => $hardware->id,
            'company_id' => $michael->company_id,
            'name' => 'Android phone',
            'serial_number' => '123',
        ]);

        $this->assertInstanceOf(
            Hardware::class,
            $hardware
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $hardware) {
            return $job->auditLog['action'] === 'hardware_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'hardware_id' => $hardware->id,
                    'hardware_name' => $hardware->name,
                ]);
        });
    }
}
