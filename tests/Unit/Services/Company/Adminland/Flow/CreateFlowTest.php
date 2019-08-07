<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Flow\CreateFlow;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_flow() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'name' => 'Selling team',
            'type' => 'employee_joins_company',
        ];

        $flow = (new CreateFlow)->execute($request);

        $this->assertDatabaseHas('flows', [
            'id' => $flow->id,
            'company_id' => $michael->company_id,
            'name' => 'Selling team',
            'type' => 'employee_joins_company',
        ]);

        $this->assertInstanceOf(
            Flow::class,
            $flow
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $flow) {
            return $job->auditLog['action'] === 'flow_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'flow_id' => $flow->id,
                    'flow_name' => $flow->name,
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
        (new CreateFlow)->execute($request);
    }
}
