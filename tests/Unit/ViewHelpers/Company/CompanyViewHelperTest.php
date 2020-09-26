<?php

namespace Tests\Unit\ViewHelpers\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Models\Company\Skill;
use App\Models\Company\Answer;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use GrahamCampbell\TestBenchCore\HelperTrait;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_the_latest_questions_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $question = factory(Question::class)->create([
            'company_id' => $michael->company_id,
            'title' => 'Do you like Dwight',
        ]);

        // now we'll call the helper again with a question that we've added answers to
        factory(Answer::class, 2)->create([
            'question_id' => $question->id,
        ]);

        $response = CompanyViewHelper::latestQuestions($michael->company);

        $this->assertArraySubset(
            [
                0 => [
                    'id' => $question->id,
                    'title' => 'Do you like Dwight',
                    'number_of_answers' => 2,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/questions/'.$question->id,
                ],
            ],
            $response['latest_questions']
        );

        $this->assertEquals(
            1,
            $response['total_number_of_questions']
        );
    }

    /** @test */
    public function it_gets_the_upcoming_birthdates_in_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 4));
        $sales = factory(Team::class)->create([]);
        $michael = factory(Employee::class)->create([
            'birthdate' => null,
            'company_id' => $sales->company_id,
        ]);
        $dwight = factory(Employee::class)->create([
            'birthdate' => '1892-01-03',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'company_id' => $sales->company_id,
        ]);
        $angela = factory(Employee::class)->create([
            'birthdate' => '1989-01-01',
            'first_name' => 'Angela',
            'last_name' => 'Bernard',
            'company_id' => $sales->company_id,
        ]);
        $john = factory(Employee::class)->create([
            'birthdate' => '1989-03-20',
            'company_id' => $sales->company_id,
        ]);
        $pamela = factory(Employee::class)->create([
            'birthdate' => '2017-12-31',
            'company_id' => $sales->company_id,
        ]);

        $array = CompanyViewHelper::birthdaysThisWeek($sales->company);

        $this->assertEquals(2, count($array));

        $this->assertEquals(
            [
                0 => [
                    'id' => $angela->id,
                    'name' => 'Angela Bernard',
                    'avatar' => $angela->avatar,
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$angela->id,
                    'birthdate' => 'January 1st',
                    'sort_key' => '2018-01-01',
                ],
                1 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $dwight->avatar,
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$dwight->id,
                    'birthdate' => 'January 3rd',
                    'sort_key' => '2018-01-03',
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_new_hires_in_the_current_week(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 4));
        $sales = factory(Team::class)->create([]);
        $michael = factory(Employee::class)->create([
            'hired_at' => null,
            'company_id' => $sales->company_id,
        ]);
        $dwight = factory(Employee::class)->create([
            'hired_at' => '2018-01-03',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'company_id' => $sales->company_id,
        ]);
        $angela = factory(Employee::class)->create([
            'hired_at' => '2018-01-01',
            'first_name' => 'Angela',
            'last_name' => 'Bernard',
            'company_id' => $sales->company_id,
        ]);
        $john = factory(Employee::class)->create([
            'hired_at' => '2018-01-08',
            'company_id' => $sales->company_id,
        ]);
        $pamela = factory(Employee::class)->create([
            'hired_at' => '2017-12-31',
            'company_id' => $sales->company_id,
        ]);

        $collection = CompanyViewHelper::newHiresThisWeek($sales->company);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $angela->id,
                    'name' => 'Angela Bernard',
                    'avatar' => $angela->avatar,
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$angela->id,
                    'hired_at' => 'January 1st',
                ],
                1 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => $dwight->avatar,
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$dwight->id,
                    'hired_at' => 'January 3rd',
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_latest_ships_created_in_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 4));
        $sales = factory(Team::class)->create([]);
        $marketing = factory(Team::class)->create([
            'company_id' => $sales->company_id,
        ]);

        $featureA = factory(Ship::class)->create([
            'team_id' => $sales->id,
        ]);
        $featureB = factory(Ship::class)->create([
            'team_id' => $marketing->id,
        ]);

        $collection = CompanyViewHelper::latestShips($sales->company);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'title' => $featureA->title,
                    'url' => env('APP_URL').'/'.$sales->company_id.'/teams/'.$sales->id.'/ships/'.$featureA->id,
                ],
                1 => [
                    'title' => $featureB->title,
                    'url' => env('APP_URL').'/'.$marketing->company_id.'/teams/'.$marketing->id.'/ships/'.$featureB->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_latest_skills_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $skillA = factory(Skill::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);
        $skillB = factory(Skill::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);

        $array = CompanyViewHelper::latestSkills($michael->company);

        $this->assertEquals(
            2,
            $array['count']
        );
        $this->assertEquals(
            [
                0 => [
                    'name' => $skillA->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/skills/'.$skillA->id,
                ],
                1 => [
                    'name' => $skillB->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/skills/'.$skillB->id,
                ],
            ],
            $array['skills']->toArray()
        );
    }
}
