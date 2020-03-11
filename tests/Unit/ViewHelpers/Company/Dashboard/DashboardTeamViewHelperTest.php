<?php

namespace Tests\Unit\ViewHelpers\Company\Dashboard;

use Carbon\Carbon;
use Tests\ApiTestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Dashboard\DashboardTeamViewHelper;

class DashboardTeamViewHelperTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_birthdates(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $sales = factory(Team::class)->create([]);
        $michael = factory(Employee::class)->create([
            'birthdate' => null,
            'company_id' => $sales->company_id,
        ]);
        $dwight = factory(Employee::class)->create([
            'birthdate' => '1892-01-29',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'company_id' => $sales->company_id,
        ]);
        $angela = factory(Employee::class)->create([
            'birthdate' => '1989-01-05',
            'first_name' => 'Angela',
            'last_name' => 'Bernard',
            'company_id' => $sales->company_id,
        ]);
        $john = factory(Employee::class)->create([
            'birthdate' => '1989-03-20',
            'company_id' => $sales->company_id,
        ]);

        $sales->employees()->syncWithoutDetaching([$michael->id]);
        $sales->employees()->syncWithoutDetaching([$dwight->id]);
        $sales->employees()->syncWithoutDetaching([$angela->id]);
        $sales->employees()->syncWithoutDetaching([$john->id]);

        $collection = DashboardTeamViewHelper::birthdays($sales);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $angela->id,
                    'name' => 'Angela Bernard',
                    'avatar' => $angela->avatar,
                    'birthdate' => [
                        'full' => 'Jan 05, 1989',
                        'year' => 1989,
                        'month' => 1,
                        'day' => 5,
                        'age' => 29,
                    ],
                    'sort_key' => '2018-01-05',
                ],
                1 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $dwight->avatar,
                    'birthdate' => [
                        'full' => 'Jan 29, 1892',
                        'year' => 1892,
                        'month' => 1,
                        'day' => 29,
                        'age' => 126,
                    ],
                    'sort_key' => '2018-01-29',
                ],
            ],
            $collection->values()->all()
        );
    }
}
