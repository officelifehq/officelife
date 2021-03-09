<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Position\DestroyPosition;

class DestroyPositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_position_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_destroys_a_position_as_hr(): void
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
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyPosition)->execute($request);
    }

    /** @test */
    public function it_fails_if_position_is_not_linked_to_company(): void
    {
        $position = Position::factory()->create([]);
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $michael->id,
            'position_id' => $position->id,
        ];

        $this->expectException(ModelNotFoundException::class);
        (new DestroyPosition)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $position = Position::factory()->create([]);
        $michael = Employee::factory()->create([
            'company_id' => $position->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $michael->id,
            'position_id' => $position->id,
        ];

        (new DestroyPosition)->execute($request);

        $this->assertDatabaseMissing('positions', [
            'id' => $position->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $position) {
            return $job->auditLog['action'] === 'position_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'position_title' => $position->title,
                ]);
        });
    }
}
