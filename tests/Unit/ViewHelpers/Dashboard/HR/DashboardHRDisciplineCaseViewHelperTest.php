<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRDisciplineCaseViewHelper;

class DashboardHRDisciplineCaseViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_an_array_about_the_opened_cases(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $openCase = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => true,
        ]);
        $closedCase = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);

        DisciplineEvent::factory()->count(3)->create([
            'discipline_case_id' => $openCase->id,
        ]);

        $array = DashboardHRDisciplineCaseViewHelper::index($michael->company);

        $this->assertEquals(1, $array['closed_cases_count']);
        $this->assertEquals(1, $array['open_cases_count']);
        $this->assertEquals(
            [
                'open' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases',
                'closed' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases/closed',
                'search' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases/employees',
                'store' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases',
            ],
            $array['url']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $openCase->id,
                    'number_of_events' => 3,
                    'opened_at' => 'Jan 01, 2018',
                    'author' => [
                        'id' => $openCase->author->id,
                        'name' => $openCase->author->name,
                        'avatar' => ImageHelper::getAvatar($openCase->author, 40),
                        'position' => $openCase->author->position->title,
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$openCase->author->id,
                    ],
                    'employee' => [
                        'id' => $openCase->employee->id,
                        'name' => $openCase->employee->name,
                        'avatar' => ImageHelper::getAvatar($openCase->employee, 40),
                        'position' => $openCase->employee->position->title,
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$openCase->employee->id,
                    ],
                    'url' => [
                        'show' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases/'.$openCase->id,
                    ],
                ],
            ],
            $array['open_cases']->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_potential_new_employees(): void
    {
        $michael = $this->createAdministrator();
        $jean = Employee::factory()->create([
            'first_name' => 'jean',
            'company_id' => $michael->company_id,
        ]);

        $collection = DashboardHRDisciplineCaseViewHelper::potentialEmployees($michael->company, 'je');
        $this->assertEquals(
            [
                0 => [
                    'id' => $jean->id,
                    'name' => $jean->name,
                    'avatar' => ImageHelper::getAvatar($jean, 64),
                ],
            ],
            $collection->toArray()
        );

        $collection = DashboardHRDisciplineCaseViewHelper::potentialEmployees($michael->company, 'roger');
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }
}
