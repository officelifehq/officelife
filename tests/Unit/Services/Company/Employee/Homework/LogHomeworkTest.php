<?php

namespace Tests\Unit\Services\Company\Employee\Homework;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Homework;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\Homework\LogHomework;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogHomeworkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_homework()
    {
        $dwight = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->user_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ];

        $homework = (new LogHomework)->execute($request);

        $this->assertDatabaseHas('homework', [
            'id' => $homework->id,
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ]);

        $this->assertInstanceOf(
            Homework::class,
            $homework
        );
    }

    /** @test */
    public function it_logs_an_action()
    {
        $dwight = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->user_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ];

        (new LogHomework)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $dwight->company_id,
            'action' => 'employee_homework_logged',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $dwight->company_id,
            'action' => 'homework_logged',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LogHomework)->execute($request);
    }
}
