<?php

namespace Tests\Unit\Services\Company\Adminland\Position;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Position\DestroyPosition;

class DestroyPositionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_position() : void
    {
        Queue::fake();

        $position = factory(Position::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $position->company_id,
        ]);

        $request = [
            'company_id' => $position->company_id,
            'author_id' => $michael->user->id,
            'position_id' => $position->id,
        ];

        (new DestroyPosition)->execute($request);

        $this->assertDatabaseMissing('positions', [
            'id' => $position->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $position) {
            return $job->auditLog['action'] === 'position_destroyed' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'position_title' => $position->title,
                ]);
        });
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyPosition)->execute($request);
    }
}
