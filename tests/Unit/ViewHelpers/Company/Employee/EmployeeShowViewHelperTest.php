<?php

namespace Tests\Unit\ViewHelpers\Company\Dashboard;

use Carbon\Carbon;
use Tests\ApiTestCase;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\WorkFromHome;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Company\Employee\EmployeeShowViewHelper;

class EmployeeShowViewHelperTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_managers(): void
    {
        $michael = factory(Employee::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $dwight->id,
            'employee_id' => $dwight->id,
            'manager_id' => $jim->id,
        ];

        (new AssignManager)->execute($request);

        $collection = EmployeeShowViewHelper::managers($dwight);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => $michael->avatar,
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => $jim->avatar,
                    'position' => [
                        'id' => $jim->position->id,
                        'title' => $jim->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_direct_reports(): void
    {
        $michael = factory(Employee::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $jim = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $collection = EmployeeShowViewHelper::directReports($michael);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => $dwight->avatar,
                    'position' => [
                        'id' => $dwight->position->id,
                        'title' => $dwight->position->title,
                    ],
                    'url' => env('APP_URL') . '/' . $dwight->company_id . '/employees/' . $dwight->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => $jim->avatar,
                    'position' => [
                        'id' => $jim->position->id,
                        'title' => $jim->position->title,
                    ],
                    'url' => env('APP_URL') . '/' . $jim->company_id . '/employees/' . $jim->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_work_logs(): void
    {
        $date = Carbon::create(2018, 10, 10);
        Carbon::setTestNow($date);
        $michael = factory(Employee::class)->create([]);

        for ($i = 0; $i < 5; $i++) {
            factory(Worklog::class)->create([
                'employee_id' => $michael->id,
                'created_at' => $date->copy()->addDay(),
            ]);
        }

        $array = EmployeeShowViewHelper::worklogs($michael);
        $this->assertEquals(2, count($array));
        $this->assertArrayHasKey(
            'worklogs_collection',
            $array
        );
        $this->assertArrayHasKey(
            'url',
            $array
        );
    }

    /** @test */
    public function it_gets_the_work_from_home_statistics(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 10, 10));

        $michael = factory(Employee::class)->create([]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2010-01-01',
            'work_from_home' => true,
        ]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2018-02-01',
            'work_from_home' => true,
        ]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2018-03-01',
            'work_from_home' => true,
        ]);
        factory(WorkFromHome::class)->create([
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'work_from_home' => true,
        ]);

        $array = EmployeeShowViewHelper::workFromHomeStats($michael);

        $this->assertEquals(3, count($array));

        $this->assertEquals(
            [
                'work_from_home_today' => true,
                'number_times_this_year' => 3,
                'url' => 'dfsd',
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/workfromhome',
            ],
            $array
        );
    }
}
