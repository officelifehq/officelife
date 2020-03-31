<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Team;
use App\Helpers\WorklogHelper;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WorklogHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_the_list_of_worklogs_for_a_given_team_and_a_given_day(): void
    {
        $date = Carbon::now();
        $team = factory(Team::class)->create([]);

        // making employees
        $dwight = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);
        $michael = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $team->employees()->syncWithoutDetaching([$dwight->id]);
        $team->employees()->syncWithoutDetaching([$michael->id]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => $date,
        ]);

        $response = WorklogHelper::getInformationAboutTeam($team, $date);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('day', $response);
        $this->assertArrayHasKey('date', $response);
        $this->assertArrayHasKey('friendlyDate', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('completionRate', $response);
        $this->assertArrayHasKey('numberOfEmployeesInTeam', $response);
        $this->assertArrayHasKey('numberOfEmployeesWhoHaveLoggedWorklogs', $response);

        $this->assertEquals(7, count($response));
    }

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

    /** @test */
    public function it_gets_a_collection_of_years_representing_all_the_years_the_employee_has_a_worklog_for(): void
    {
        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-01-01 00:00:00',
        ]);
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '1990-01-01 00:00:00',
        ]);

        $worklogs = $dwight->worklogs()->orderBy('worklogs.created_at')->get();

        $collection = WorklogHelper::getYears($worklogs);

        $this->assertEquals(
            [
                0 => [
                    'number' => 1990,
                ],
                1 => [
                    'number' => 2020,
                ],
            ],
            $collection->toArray()
        );

        $this->assertInstanceOf(
            Collection::class,
            $collection
        );
    }

    /** @test */
    public function it_gets_a_collection_of_months_representing_all_the_months_the_employee_has_a_worklog_for(): void
    {
        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-01-01 00:00:00',
        ]);
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-02-01 00:00:00',
        ]);
        factory(Worklog::class, 2)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-03-01 00:00:00',
        ]);

        $worklogs = $dwight->worklogs;

        $collection = WorklogHelper::getMonths($worklogs, 2020);

        $this->assertEquals(
            [
                0 => [
                    'month' => 1,
                    'occurences' => 1,
                    'translation' => 'January',
                ],
                1 => [
                    'month' => 2,
                    'occurences' => 1,
                    'translation' => 'February',
                ],
                2 => [
                    'month' => 3,
                    'occurences' => 2,
                    'translation' => 'March',
                ],
                3 => [
                    'month' => 4,
                    'occurences' => 0,
                    'translation' => 'April',
                ],
                4 => [
                    'month' => 5,
                    'occurences' => 0,
                    'translation' => 'May',
                ],
                5 => [
                    'month' => 6,
                    'occurences' => 0,
                    'translation' => 'June',
                ],
                6 => [
                    'month' => 7,
                    'occurences' => 0,
                    'translation' => 'July',
                ],
                7 => [
                    'month' => 8,
                    'occurences' => 0,
                    'translation' => 'August',
                ],
                8 => [
                    'month' => 9,
                    'occurences' => 0,
                    'translation' => 'September',
                ],
                9 => [
                    'month' => 10,
                    'occurences' => 0,
                    'translation' => 'October',
                ],
                10 => [
                    'month' => 11,
                    'occurences' => 0,
                    'translation' => 'November',
                ],
                11 => [
                    'month' => 12,
                    'occurences' => 0,
                    'translation' => 'December',
                ],
            ],
            $collection->toArray()
        );

        $this->assertInstanceOf(
            Collection::class,
            $collection
        );
    }

    /** @test */
    public function it_gets_a_collection_representing_all_the_worklogs_for_a_given_year(): void
    {
        $dwight = factory(Employee::class)->create([]);

        // logging worklogs
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-01-01 00:00:00',
        ]);
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-02-01 00:00:00',
        ]);
        factory(Worklog::class, 2)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2020-03-01 00:00:00',
        ]);

        $worklogs = $dwight->worklogs;

        $collection = WorklogHelper::getYearlyCalendar($worklogs, 2020);

        $this->assertArraySubset(
            [
                'date' => '2020-01-02',
                'count' => 1,
            ],
            $collection[0]
        );
        $this->assertArraySubset(
            [
                'date' => '2020-01-03',
                'count' => 0,
            ],
            $collection[1]
        );

        $this->assertEquals(
            366,
            $collection->count()
        );

        $this->assertInstanceOf(
            Collection::class,
            $collection
        );
    }
}
