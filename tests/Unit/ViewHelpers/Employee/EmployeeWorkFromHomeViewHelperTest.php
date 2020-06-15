<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Tests\ApiTestCase;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use App\Models\Company\WorkFromHome;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeWorkFromHomeViewHelper;

class EmployeeWorkFromHomeViewHelperTest extends ApiTestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_years_representing_all_the_years_the_employee_has_a_worklog_for(): void
    {
        $michael = factory(Employee::class)->create([]);

        // logging worklogs
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2020-01-01 00:00:00',
        ]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '1990-01-01 00:00:00',
        ]);

        $entries = $michael->workFromHomes()->orderBy('employee_work_from_home.date')->get();

        $collection = EmployeeWorkFromHomeViewHelper::yearsWithEntries($entries);

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
    public function it_gets_a_collection_of_months_representing_all_the_months_the_employee_has_been_working_from_home(): void
    {
        $michael = factory(Employee::class)->create([]);

        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2020-01-01',
        ]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2020-02-01',
        ]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2020-03-01',
        ]);

        $entries = $michael->workFromHomes;

        $collection = EmployeeWorkFromHomeViewHelper::monthsWithEntries($entries, 2020);

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
                    'occurences' => 1,
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
}
