<?php

namespace Tests\Unit\ViewHelpers\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Models\Company\Skill;
use App\Models\Company\Answer;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\Question;
use App\Models\Company\CompanyNews;
use GrahamCampbell\TestBenchCore\HelperTrait;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\GuessEmployeeGame\CreateGuessEmployeeGame;

class CompanyViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_information_about_the_company(): void
    {
        $michael = $this->createAdministrator();
        Team::factory()->count(2)->create([
            'company_id' => $michael->company_id,
        ]);

        Employee::factory()->count(2)->create([
            'company_id' => $michael->company_id,
        ]);

        $file = File::factory()->create();
        $michael->company->logo_file_id = $file->id;
        $michael->company->founded_at = '2020:01:01 00:00:00';
        $michael->company->save();

        $response = CompanyViewHelper::information($michael->company);

        $this->assertEquals(
            [
                'number_of_teams' => 2,
                'number_of_employees' => 3,
                'logo' => ImageHelper::getImage($file, 75, 75),
                'founded_at' => 2020,
            ],
            $response
        );
    }

    /** @test */
    public function it_gets_the_latest_questions_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $question = Question::factory()->create([
            'company_id' => $michael->company_id,
            'title' => 'Do you like Dwight',
            'active' => false,
        ]);
        $questionB = Question::factory()->create([
            'company_id' => $michael->company_id,
            'title' => 'Do you like Michael',
            'active' => true,
        ]);

        // now we'll call the helper again with a question that we've added answers to
        Answer::factory()->count(2)->create([
            'question_id' => $question->id,
        ]);

        $response = CompanyViewHelper::latestQuestions($michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $question->id,
                    'title' => 'Do you like Dwight',
                    'number_of_answers' => 2,
                    'active' => false,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/questions/'.$question->id,
                ],
            ],
            $response['questions']->toArray()
        );

        $this->assertEquals(
            [
                'id' => $questionB->id,
                'title' => 'Do you like Michael',
                'number_of_answers' => 0,
                'active' => true,
                'url' => env('APP_URL') . '/' . $michael->company_id . '/company/questions/' . $questionB->id,
            ],
            $response['active_question']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/questions',
            $response['all_questions_url']
        );

        $this->assertEquals(
            2,
            $response['total_number_of_questions']
        );
    }

    /** @test */
    public function it_gets_the_upcoming_birthdates_in_the_company(): void
    {
        // today is Wednesday, Jan 10 2018
        // so first day of week is Monday, Jan 8th
        // last day is Sunday, Jan 14th
        Carbon::setTestNow(Carbon::create(2018, 1, 10));
        $sales = Team::factory()->create([]);

        //creating a bunch of employees that should not be in the list of birthdates
        $michael = Employee::factory()->create([
            'birthdate' => null,
            'company_id' => $sales->company_id,
        ]);
        $john = Employee::factory()->create([
            'birthdate' => '1989-01-07',
            'company_id' => $sales->company_id,
        ]);
        $pamela = Employee::factory()->create([
            'birthdate' => '2017-01-15',
            'company_id' => $sales->company_id,
        ]);

        // employees who should be in the list of birthdates for the week
        $dwight = Employee::factory()->create([
            'birthdate' => '1892-01-08',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'company_id' => $sales->company_id,
        ]);
        $angela = Employee::factory()->create([
            'birthdate' => '1989-01-14',
            'first_name' => 'Angela',
            'last_name' => 'Bernard',
            'company_id' => $sales->company_id,
        ]);

        $array = CompanyViewHelper::birthdaysThisWeek($sales->company);

        $this->assertEquals(2, count($array));

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => ImageHelper::getAvatar($dwight, 35),
                    'birthdate' => 'January 8th',
                    'sort_key' => '2018-01-08',
                    'url' => env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
                ],
                1 => [
                    'id' => $angela->id,
                    'name' => 'Angela Bernard',
                    'avatar' => ImageHelper::getAvatar($angela, 35),
                    'birthdate' => 'January 14th',
                    'sort_key' => '2018-01-14',
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$angela->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_new_hires_in_the_current_week(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 4));
        $sales = Team::factory()->create([]);
        $michael = Employee::factory()->create([
            'hired_at' => null,
            'company_id' => $sales->company_id,
        ]);
        $dwight = Employee::factory()->create([
            'hired_at' => '2018-01-05',
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'company_id' => $sales->company_id,
        ]);
        $angela = Employee::factory()->create([
            'hired_at' => '2018-01-01',
            'first_name' => 'Angela',
            'last_name' => 'Bernard',
            'company_id' => $sales->company_id,
        ]);
        $john = Employee::factory()->create([
            'hired_at' => '2018-01-08',
            'company_id' => $sales->company_id,
        ]);
        $pamela = Employee::factory()->create([
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
                    'avatar' => ImageHelper::getAvatar($angela, 35),
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$angela->id,
                    'hired_at' => 'Started on Monday (Jan 1st) as Assistant to the regional manager',
                ],
                1 => [
                    'id' => $dwight->id,
                    'name' => 'Dwight Schrute',
                    'avatar' => ImageHelper::getAvatar($dwight, 35),
                    'url' => env('APP_URL').'/'.$angela->company_id.'/employees/'.$dwight->id,
                    'hired_at' => 'Starts on Friday (Jan 5th) as Assistant to the regional manager',
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_latest_ships_created_in_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 4));
        $sales = Team::factory()->create([]);
        $marketing = Team::factory()->create([
            'company_id' => $sales->company_id,
        ]);

        $featureA = Ship::factory()->create([
            'team_id' => $sales->id,
        ]);
        $featureB = Ship::factory()->create([
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
        $skillA = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);
        $skillB = Skill::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'php',
        ]);

        $array = CompanyViewHelper::latestSkills($michael->company);

        $this->assertEquals(
            2,
            $array['count']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/skills',
            $array['view_all_url']
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

    /** @test */
    public function it_gets_the_latest_news(): void
    {
        $michael = $this->createAdministrator();
        $newsA = CompanyNews::factory()->create([
            'company_id' => $michael->company_id,
            'title' => 'php',
            'content' => 'this is a test',
            'author_name' => 'regis',
        ]);
        $newsB = CompanyNews::factory()->create([
            'company_id' => $michael->company_id,
            'title' => 'php',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ac quam viverra nec consectetur ante hendrerit. Donec et mollis dolor. Praesent et diam eget libero egestas mattis sit amet vitae augue. Nam tincidunt congue enim, ut porta lorem lacinia consectetur. Donec ut libero sed arcu vehicula ultricies a non tortor.',
            'author_name' => 'regis',
        ]);

        $array = CompanyViewHelper::latestNews($michael->company);

        $this->assertEquals(
            2,
            $array['count']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/news',
            $array['view_all_url']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $newsB->id,
                    'title' => $newsB->title,
                    'extract' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris. Maecenas congue ligula ...</p>',
                    'author_name' => $newsB->author_name,
                ],
                1 => [
                    'id' => $newsA->id,
                    'title' => $newsA->title,
                    'extract' => '<p>this is a test</p>',
                    'author_name' => $newsA->author_name,
                ],
            ],
            $array['news']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_guess_employees_game(): void
    {
        $michael = Employee::factory()->create([
            'pronoun_id' => 1,
        ]);
        Employee::factory()->count(3)->create([
            'company_id' => $michael->company_id,
            'pronoun_id' => 1,
        ]);

        $game = (new CreateGuessEmployeeGame)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
        ]);

        $array = CompanyViewHelper::guessEmployeeGameInformation($michael, $michael->company);

        $this->assertEquals(
            $game->id,
            $array['id']
        );

        $this->assertEquals(
            ImageHelper::getAvatar($game->employeeToFind, 80),
            $array['avatar_to_find']
        );

        $this->assertEquals(
            3,
            count($array['choices'])
        );
    }

    /** @test */
    public function it_gets_the_information_about_employees(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // one employee hired in the current year
        $michael = Employee::factory()->create([
            'hired_at' => Carbon::now(),
        ]);

        // one employee hired last year
        $position = Position::factory()->create();
        Employee::factory()->create([
            'hired_at' => Carbon::now()->subYear(),
            'position_id' => $position->id,
        ]);

        $array = CompanyViewHelper::employees($michael->company);

        $this->assertEquals(
            1,
            $array['employees_hired_in_the_current_year']
        );

        $this->assertEquals(
            0,
            $array['number_of_employees_left']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 32),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array['ten_random_employees']->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_teams(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $team->employees()->attach($michael->id);

        $array = CompanyViewHelper::teams($michael->company);

        $this->assertEquals(
            $team->id,
            $array['random_teams']->toArray()[0]['id']
        );
        $this->assertEquals(
            $team->name,
            $array['random_teams']->toArray()[0]['name']
        );
        $this->assertEquals(
            0,
            $array['random_teams']->toArray()[0]['total_remaining_employees']
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/teams',
            $array['view_all_url']
        );
    }

    /** @test */
    public function it_gets_a_list_of_upcoming_hiring_date_anniversaries(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create([]);

        $dwight = Employee::factory()->create([
            'company_id' => $company->id,
            'hired_at' => '1990-01-02 00:00:00',
        ]);
        $michael = Employee::factory()->create([
            'company_id' => $company->id,
            'hired_at' => '1990-05-01 00:00:00',
        ]);
        $jim = Employee::factory()->create([
            'company_id' => $company->id,
            'hired_at' => '2017-04-02 00:00:00',
            'locked' => true,
        ]);

        $collection = CompanyViewHelper::upcomingHiredDateAnniversaries($company);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => ImageHelper::getAvatar($dwight, 35),
                    'anniversary_date' => 'Tuesday (Jan 2nd)',
                    'anniversary_age' => '28',
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id,
                ],
            ],
            $collection->toArray()
        );
    }
}
