<?php

namespace Tests\Unit\Services\Company\Adminland\Flow;

use Tests\TestCase;
use App\Models\Company\Flow;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Flow\CreateFlow;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateFlowTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_flow() : void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'name' => 'Selling team',
            'type' => 'employee_joins_company',
        ];

        $flow = (new CreateFlow)->execute($request);

        $this->assertDatabaseHas('flows', [
            'id' => $flow->id,
            'company_id' => $employee->company_id,
            'name' => 'Selling team',
            'type' => 'employee_joins_company',
        ]);

        $this->assertInstanceOf(
            Flow::class,
            $flow
        );
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'name' => 'Selling team',
            'type' => 'employee_joins_company',
        ];

        $flow = (new CreateFlow)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'flow_created',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'flow_id' => $flow->id,
                'flow_name' => $flow->name,
            ]),
        ]);
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
