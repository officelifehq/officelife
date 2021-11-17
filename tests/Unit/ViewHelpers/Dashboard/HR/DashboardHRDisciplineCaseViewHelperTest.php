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
        DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
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

    /** @test */
    public function it_gets_a_dto_about_the_case(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => false,
        ]);
        DisciplineEvent::factory()->count(3)->create([
            'discipline_case_id' => $case->id,
        ]);

        $array = DashboardHRDisciplineCaseViewHelper::dto($michael->company, $case);

        $this->assertEquals(
            [
                'id' => $case->id,
                'opened_at' => 'Jan 01, 2018',
                'author' => [
                    'id' => $case->author->id,
                    'name' => $case->author->name,
                    'avatar' => ImageHelper::getAvatar($case->author, 40),
                    'position' => $case->author->position->title,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$case->author->id,
                ],
                'employee' => [
                    'id' => $case->employee->id,
                    'name' => $case->employee->name,
                    'avatar' => ImageHelper::getAvatar($case->employee, 40),
                    'position' => $case->employee->position->title,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$case->employee->id,
                ],
                'url' => [
                    'show' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases/'.$case->id,
                ],
            ],
            $array
        );
    }
}
