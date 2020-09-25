<?php

namespace Tests\Unit\ViewHelpers\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Team;
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
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
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
            'birthdate' => '1989-01-02',
            'first_name' => 'Angela',
            'last_name' => 'Bernard',
            'company_id' => $sales->company_id,
        ]);
        $john = factory(Employee::class)->create([
            'birthdate' => '1989-03-20',
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
                    'birthdate' => 'January 2nd',
                    'sort_key' => '2018-01-02',
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
}
