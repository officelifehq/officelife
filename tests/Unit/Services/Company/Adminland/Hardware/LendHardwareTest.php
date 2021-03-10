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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Hardware\LendHardware;
use App\Services\Company\Adminland\Question\ActivateQuestion;

class LendHardwareTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_lends_a_hardware_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $hardware = Hardware::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $hardware);
    }

    /** @test */
    public function it_lends_a_hardware_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $hardware = Hardware::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $hardware);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $hardware = Hardware::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $hardware);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant Regional Manager',
        ];

        $this->expectException(ValidationException::class);
        (new ActivateQuestion)->execute($request);
    }

    /** @test */
    public function it_fails_if_hardware_is_not_linked_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $hardware = Hardware::factory()->create([]);
        $this->executeService($michael, $dwight, $hardware);
    }

    /** @test */
    public function it_fails_if_employee_is_not_linked_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $hardware = Hardware::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $dwight, $hardware);
    }

    private function executeService(Employee $michael, Employee $dwight, Hardware $hardware): void
    {
        Queue::fake();

        $request = [
            'company_id' => $hardware->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'hardware_id' => $hardware->id,
        ];

        (new LendHardware)->execute($request);

        $this->assertDatabaseHas('hardware', [
            'id' => $hardware->id,
            'company_id' => $hardware->company_id,
            'employee_id' => $dwight->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight, $hardware) {
            return $job->auditLog['action'] === 'hardware_lent' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'hardware_id' => $hardware->id,
                    'hardware_name' => $hardware->name,
                    'employee_name' => $dwight->name,
                ]);
        });
    }
}
