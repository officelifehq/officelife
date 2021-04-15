<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeWorkViewHelper;

class EmployeeWorkViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_details_of_a_worklog(): void
    {
        $date = Carbon::create(2018, 1, 1);
        Carbon::setTestNow($date);
        $startOfWeek = $date->copy()->startOfWeek();

        $michael = $this->createAdministrator();

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->startOfWeek()->addDays($i);

            Worklog::factory()->create([
                'employee_id' => $michael->id,
                'content' => 'test',
                'created_at' => $day,
            ]);
            Morale::factory()->create([
                'employee_id' => $michael->id,
                'created_at' => $day,
            ]);
        }

        $array = EmployeeWorkViewHelper::worklog($michael, $michael, $startOfWeek, $date);

        $this->assertEquals(7, count($array['days']->toArray()));

        $this->assertEquals(
            '2018-01-01',
            $array['current_week']
        );
        $this->assertEquals(
            '<p>test</p>',
            $array['worklog_parsed_content']
        );

        $this->assertEquals(
            '😡 I’ve had a bad day',
            $array['morale']
        );

        $this->assertEquals(
            7,
            $array['days']->count()
        );
    }

    /** @test */
    public function it_gets_the_different_weeks(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $collection = EmployeeWorkViewHelper::weeks($michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => 1,
                    'label' => '3 weeks ago',
                    'range' => [
                        'start' => 'December 11th',
                        'end' => 'December 17th',
                    ],
                    'start_of_week_date' => '2017-12-11',
                ],
                1 => [
                    'id' => 2,
                    'label' => '2 weeks ago',
                    'range' => [
                        'start' => 'December 18th',
                        'end' => 'December 24th',
                    ],
                    'start_of_week_date' => '2017-12-18',
                ],
                2 => [
                    'id' => 3,
                    'label' => 'Last week',
                    'range' => [
                        'start' => 'December 25th',
                        'end' => 'December 31st',
                    ],
                    'start_of_week_date' => '2017-12-25',
                ],
                3 => [
                    'id' => 4,
                    'label' => 'Current week',
                    'range' => [
                        'start' => 'January 1st',
                        'end' => 'January 7th',
                    ],
                    'start_of_week_date' => '2018-01-01',
                ],
            ],
            $collection->toArray()
        );
    }
}
