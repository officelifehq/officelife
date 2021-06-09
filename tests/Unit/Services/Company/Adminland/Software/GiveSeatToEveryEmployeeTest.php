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
use App\Services\Company\Adminland\Software\GiveSeatToEveryEmployee;

class GiveSeatToEveryEmployeeTest extends TestCase
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
        $software->employees()->syncWithoutDetaching([$michael->id]);
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
        $software->employees()->syncWithoutDetaching([$michael->id]);
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
        $software->employees()->syncWithoutDetaching([$michael->id]);
        $this->executeService($michael, $software, $dwight);
    }

    /** @test */
    public function it_doesnt_fail_if_seat_number_is_too_low(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
            'seats' => 0,
        ]);
        $software->employees()->syncWithoutDetaching([$michael->id]);

        $this->executeService($michael, $software, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new GiveSeatToEveryEmployee)->execute($request);
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

        (new GiveSeatToEveryEmployee)->execute($request);

        $this->assertDatabaseHas('employee_software', [
            'software_id' => $software->id,
            'employee_id' => $dwight->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $software) {
            return $job->auditLog['action'] === 'software_seat_given_to_all_remaining_employees' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'software_id' => $software->id,
                    'software_name' => $software->name,
                ]);
        });
    }
}
