<?php

namespace Tests\Unit\ViewHelpers\Company\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\HR\CompanyHRAskMeAnythingViewHelper;

class CompanyHRAskMeAnythingViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_all_the_ama_sessions(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create();
        $michael = $this->createAdministrator();

        $activeAma = AskMeAnythingSession::factory()->create([
            'company_id' => $company->id,
            'theme' => 'theme',
            'happened_at' => Carbon::now()->addDay(),
            'active' => true,
        ]);
        AskMeAnythingQuestion::factory()->count(2)->create([
            'ask_me_anything_session_id' => $activeAma->id,
        ]);

        $inactiveAma = AskMeAnythingSession::factory()->create([
            'company_id' => $company->id,
            'theme' => 'other theme',
            'happened_at' => Carbon::now()->addDay(),
            'active' => false,
        ]);
        AskMeAnythingQuestion::factory()->count(2)->create([
            'ask_me_anything_session_id' => $inactiveAma->id,
        ]);

        $array = CompanyHRAskMeAnythingViewHelper::index($company, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $inactiveAma->id,
                    'active' => false,
                    'theme' => 'other theme',
                    'happened_at' => 'Jan 02, 2018',
                    'questions_count' => 2,
                    'url' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$inactiveAma->id,
                ],
            ],
            $array['inactive_sessions']->toArray()
        );
        $this->assertEquals(
            [
                'id' => $activeAma->id,
                'theme' => 'theme',
                'happened_at' => 'Jan 02, 2018',
                'questions_count' => 2,
                'url' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$activeAma->id,
            ],
            $array['active_session']
        );
        $this->assertTrue($array['can_create']);
        $this->assertEquals(
            env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/create',
            $array['url_new']
        );

        $dwight = $this->createEmployee();
        $array = CompanyHRAskMeAnythingViewHelper::index($company, $dwight);
        $this->assertFalse($array['can_create']);
    }

    /** @test */
    public function it_gets_the_information_needed_for_the_create_screen(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create();

        $array = CompanyHRAskMeAnythingViewHelper::new($company);

        $this->assertEquals(
            [
                'url_back' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything',
                'url_submit' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything',
                'year' => 2018,
                'month' => 01,
                'day' => 01,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_details_of_a_given_ama_session(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create();
        $michael = $this->createAdministrator();

        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $company->id,
            'theme' => 'theme',
            'happened_at' => Carbon::now()->addDay(),
        ]);
        $question = AskMeAnythingQuestion::factory()->create([
            'ask_me_anything_session_id' => $ama->id,
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
        ]);
        $anonymousQuestion = AskMeAnythingQuestion::factory()->create([
            'ask_me_anything_session_id' => $ama->id,
            'anonymous' => true,
        ]);

        $array = CompanyHRAskMeAnythingViewHelper::show($company, $ama, $michael, false);

        $this->assertEquals(
            $ama->id,
            $array['id']
        );
        $this->assertEquals(
            false,
            $array['active']
        );
        $this->assertEquals(
            'theme',
            $array['theme']
        );
        $this->assertEquals(
            'Jan 02, 2018',
            $array['happened_at']
        );
        $this->assertEquals(
            [
                'unanswered_tab' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id,
                'answered_tab' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id.'/answered',
                'edit' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id.'/edit',
                'delete' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id.'/delete',
                'toggle' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id.'/toggle',
            ],
            $array['url']
        );
        $this->assertEquals(
            [
                'can_mark_answered' => true,
                'can_edit' => true,
            ],
            $array['permissions']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $question->id,
                    'question' => $question->question,
                    'answered' => $question->answered,
                    'author' => [
                        'id' => $question->employee->id,
                        'name' => $question->employee->name,
                        'avatar' => ImageHelper::getAvatar($question->employee, 22),
                        'position' => (! $question->employee->position) ? null : [
                            'id' => $question->employee->position->id,
                            'title' => $question->employee->position->title,
                        ],
                        'url_view' => env('APP_URL').'/'.$company->id.'/employees/'.$question->employee->id,
                    ],
                    'url_toggle' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id.'/questions/'.$question->id,
                ],
                1 => [
                    'id' => $anonymousQuestion->id,
                    'question' => $anonymousQuestion->question,
                    'answered' => $anonymousQuestion->answered,
                    'author' => null,
                    'url_toggle' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id.'/questions/'.$anonymousQuestion->id,
                ],
            ],
            $array['questions']->toArray()
        );

        $michael = $this->createEmployee();
        $array = CompanyHRAskMeAnythingViewHelper::show($company, $ama, $michael, false);
        $this->assertEquals(
            [
                'can_mark_answered' => false,
                'can_edit' => false,
            ],
            $array['permissions']
        );
    }

    /** @test */
    public function it_gets_the_details_to_edit_a_given_ama_session(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create();

        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $company->id,
            'theme' => 'theme',
            'happened_at' => Carbon::now()->addDay(),
        ]);

        $array = CompanyHRAskMeAnythingViewHelper::edit($company, $ama);

        $this->assertEquals(
            [
                'id' => $ama->id,
                'theme' => $ama->theme,
                'happened_at_year' => $ama->happened_at->year,
                'happened_at_month' => $ama->happened_at->month,
                'happened_at_day' => $ama->happened_at->day,
                'url' => [
                    'update' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id,
                    'back' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_details_to_delete_a_given_ama_session(): void
    {
        $company = Company::factory()->create();

        $ama = AskMeAnythingSession::factory()->create([
            'company_id' => $company->id,
        ]);

        $array = CompanyHRAskMeAnythingViewHelper::delete($company, $ama);

        $this->assertEquals(
            [
                'id' => $ama->id,
                'url' => [
                    'destroy' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id,
                    'back' => env('APP_URL').'/'.$company->id.'/company/hr/ask-me-anything/'.$ama->id,
                ],
            ],
            $array
        );
    }
}
