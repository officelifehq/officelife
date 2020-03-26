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
use App\Services\Company\Adminland\Position\UpdatePosition;

class UpdatePositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_position_as_administrator(): void
    {
        $this->executeService(config('officelife.permission_level.administrator'));
    }

    /** @test */
    public function it_updates_a_position_as_hr(): void
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
            'title' => 'Assistant Regional Manager',
        ];

        $this->expectException(ValidationException::class);
        (new UpdatePosition)->execute($request);
    }

    /** @test */
    public function it_fails_if_position_is_not_linked_to_company(): void
    {
        $position = factory(Position::class)->create([]);
        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $michael->id,
            'position_id' => $position->id,
            'title' => 'Assistant Regional Manager',
        ];

        $this->expectException(ModelNotFoundException::class);
        (new UpdatePosition)->execute($request);
    }

    private function executeService(int $permissionLevel): void
    {
        Queue::fake();

        $position = factory(Position::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $position->company_id,
            'permission_level' => $permissionLevel,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $michael->id,
            'position_id' => $position->id,
            'title' => 'Assistant Regional Manager',
        ];

        $newPosition = (new UpdatePosition)->execute($request);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'company_id' => $position->company_id,
            'title' => 'Assistant Regional Manager',
        ]);

        $this->assertInstanceOf(
            Position::class,
            $position
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $position, $newPosition) {
            return $job->auditLog['action'] === 'position_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'position_id' => $newPosition->id,
                    'position_title' => $newPosition->title,
                    'position_old_title' => $position->title,
                ]);
        });
    }
}
