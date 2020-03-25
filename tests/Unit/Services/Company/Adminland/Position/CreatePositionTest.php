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
use App\Services\Company\Adminland\Position\CreatePosition;

class CreatePositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_position_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_position_as_hr(): void
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
        (new CreatePosition)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'Assistant to the regional manager',
        ];

        $position = (new CreatePosition)->execute($request);

        $this->assertDatabaseHas('positions', [
            'id' => $position->id,
            'company_id' => $michael->company_id,
            'title' => 'Assistant to the regional manager',
        ]);

        $this->assertInstanceOf(
            Position::class,
            $position
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $position) {
            return $job->auditLog['action'] === 'position_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'position_id' => $position->id,
                    'position_title' => $position->title,
                ]);
        });
    }
}
