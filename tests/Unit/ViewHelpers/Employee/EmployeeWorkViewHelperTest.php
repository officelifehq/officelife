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
    public function it_gets_a_collection_of_worklogs(): void
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

        $array = EmployeeWorkViewHelper::worklogs($michael, $michael, $startOfWeek);

        $this->assertEquals(2, count($array));

        $this->assertArrayHasKey(
            'data',
            $array
        );

        $this->assertArrayHasKey(
            'url',
            $array
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/work/worklogs',
            $array['url']
        );

        $this->assertEquals(7, count($array['data']->toArray()));

        $this->assertEquals(
            'Monday (Jan 1st)',
            $array['data']->toArray()[0]['date']
        );
        $this->assertEquals(
            'current',
            $array['data']->toArray()[0]['status']
        );
        $this->assertEquals(
            '<p>test</p>',
            $array['data']->toArray()[0]['worklog_parsed_content']
        );
        $this->assertEquals(
            '😡 I’ve had a bad day',
            $array['data']->toArray()[0]['morale']
        );
    }
}
