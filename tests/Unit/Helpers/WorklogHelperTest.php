<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\WorklogHelper;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorklogHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_the_content_of_the_worklog_for_a_given_employee_and_a_given_day(): void
    {
        $date = Carbon::now();

        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        $worklog = factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);

        // logging morale
        $morale = factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'emotion' => 1,
        ]);

        $response = WorklogHelper::getDailyInformationForEmployee($date, $worklog, $morale);
        $this->assertIsArray($response);

        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('friendly_date', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('worklog_parsed_content', $response);
        $this->assertArrayHasKey('morale', $response);

        $this->assertEquals(5, count($response));
    }

    /** @test */
    public function it_gets_the_content_of_the_worklog_for_a_given_employee_and_a_given_day_without_morale(): void
    {
        $date = Carbon::now();

        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        $worklog = factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);

        $response = WorklogHelper::getDailyInformationForEmployee($date, $worklog, null);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('friendly_date', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('worklog_parsed_content', $response);
        $this->assertArrayHasKey('morale', $response);

        $this->assertEquals(5, count($response));
    }
}
