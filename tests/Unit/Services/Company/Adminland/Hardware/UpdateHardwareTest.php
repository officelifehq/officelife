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
use App\Services\Company\Adminland\Hardware\UpdateHardware;

class UpdateHardwareTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_hardware_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_updates_a_hardware_as_hr(): void
    {
        $this->executeService(config('officelife.permission_level.hr'));
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(NotEnoughPermissionException::class);

        $this->executeService(config('officelife.permission_level.user'));
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Android phone',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateHardware)->execute($request);
    }

    /** @test */
    public function it_fails_if_position_is_not_linked_to_company(): void
    {
        $hardware = factory(Hardware::class)->create([]);
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $hardware->company_id,
            'author_id' => $michael->id,
            'hardware_id' => $hardware->id,
            'name' => 'Android phone',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdateHardware)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $hardware = factory(Hardware::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $hardware->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $hardware->company_id,
            'author_id' => $michael->id,
            'hardware_id' => $hardware->id,
            'name' => 'Android phone',
            'serial_number' => '1234',
        ];

        $newHardware = (new UpdateHardware)->execute($request);

        $this->assertDatabaseHas('hardware', [
            'id' => $hardware->id,
            'company_id' => $hardware->company_id,
            'name' => 'Android phone',
            'serial_number' => '1234',
        ]);

        $this->assertInstanceOf(
            Hardware::class,
            $hardware
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $hardware, $newHardware) {
            return $job->auditLog['action'] === 'hardware_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'hardware_id' => $hardware->id,
                    'hardware_name' => $newHardware->name,
                    'hardware_old_name' => $hardware->name,
                ]);
        });
    }
}
