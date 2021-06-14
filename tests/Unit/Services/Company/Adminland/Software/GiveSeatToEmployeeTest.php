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
use App\Services\Company\Adminland\Software\GiveSeatToEmployee;

class GiveSeatToEmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gives_a_software_seat_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $software, $dwight);
    }

    /** @test */
    public function it_gives_a_software_seat_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $software, $dwight);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $software, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new GiveSeatToEmployee)->execute($request);
    }

    private function executeService(Employee $michael, Software $software, Employee $dwight): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'software_id' => $software->id,
            'employee_id' => $dwight->id,
            'product_key' => '123',
            'notes' => 'private note',
        ];

        (new GiveSeatToEmployee)->execute($request);

        $this->assertDatabaseHas('employee_software', [
            'software_id' => $software->id,
            'employee_id' => $dwight->id,
            'product_key' => '123',
            'notes' => 'private note',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $software, $dwight) {
            return $job->auditLog['action'] === 'software_seat_given_to_employee' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'software_id' => $software->id,
                    'software_name' => $software->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });
    }
}
