<?php

namespace Tests\Unit\Services\Company\Employee\Morale;

use Carbon\Carbon;
use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Morale;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\Morale\LogMorale;
use App\Exceptions\MoraleAlreadyLoggedTodayException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogMoraleTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_morale() : void
    {
        Queue::fake();

        $dwight = factory(Employee::class)->create([]);

        $request = [
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'emotion' => 1,
            'comment' => 'Michael is my idol',
        ];

        $morale = (new LogMorale)->execute($request);

        $this->assertDatabaseHas('morale', [
            'id' => $morale->id,
            'employee_id' => $dwight->id,
            'emotion' => 1,
            'comment' => 'Michael is my idol',
            'is_dummy' => false,
        ]);

        $this->assertInstanceOf(
            Morale::class,
            $morale
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($dwight, $morale) {
            return $job->auditLog['action'] === 'employee_morale_logged' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'morale_id' => $morale->id,
                    'emotion' => $morale->emotion,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($dwight, $morale) {
            return $job->auditLog['action'] === 'morale_logged' &&
                $job->auditLog['author_id'] === $dwight->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'morale_id' => $morale->id,
                    'emotion' => $morale->emotion,
                ]);
        });
    }

    /** @test */
    public function it_doesnt_let_record_morale_if_one_has_already_been_submitted_today() : void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $dwight = factory(Employee::class)->create([]);
        factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => now(),
        ]);

        $request = [
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'emotion' => 1,
            'comment' => 'Michael is my idol',
        ];

        $this->expectException(MoraleAlreadyLoggedTodayException::class);
        (new LogMorale)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LogMorale)->execute($request);
    }
}
