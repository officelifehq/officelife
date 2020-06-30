<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Tests\TestCase;
use App\Models\Company\Task;
use App\Models\Company\Answer;
use App\Models\Company\Question;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;

class DashboardMeViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_an_empty_array_if_there_is_no_active_question_in_the_company(): void
    {
        $michael = $this->createAdministrator();

        $this->assertNull(
            DashboardMeViewHelper::question($michael)
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_current_active_question(): void
    {
        $michael = $this->createAdministrator();
        $question = factory(Question::class)->create([
            'company_id' => $michael->company_id,
            'title' => 'Do you like Dwight',
            'active' => true,
        ]);

        factory(Answer::class, 2)->create([
            'question_id' => $question->id,
        ]);

        $response = DashboardMeViewHelper::question($michael);

        $this->assertArraySubset(
            [
                'title' => 'Do you like Dwight',
                'number_of_answers' => 2,
                'answer_by_employee' => null,
            ],
            $response
        );

        $this->assertArraySubset(
            [
                'title' => 'Do you like Dwight',
                'number_of_answers' => 2,
                'employee_has_answered' => false,
                'answer_by_employee' => null,
            ],
            $response
        );

        factory(Answer::class)->create([
            'employee_id' => $michael->id,
            'question_id' => $question->id,
        ]);

        $response = DashboardMeViewHelper::question($michael);

        $this->assertIsArray($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('title', $response);
        $this->assertArrayHasKey('employee_has_answered', $response);
        $this->assertArrayHasKey('number_of_answers', $response);
        $this->assertArrayHasKey('answers', $response);
        $this->assertArrayHasKey('answer_by_employee', $response);
        $this->assertArrayHasKey('url', $response);

        $this->assertArraySubset(
            [
                'title' => 'Do you like Dwight',
                'number_of_answers' => 3,
                'employee_has_answered' => true,
                'url' => env('APP_URL').'/'.$michael->company_id.'/company/questions/'.$question->id,
            ],
            DashboardMeViewHelper::question($michael)
        );
    }

    /** @test */
    public function it_gets_the_information_about_the_inprogress_tasks_of_the_employee(): void
    {
        $michael = $this->createAdministrator();
        $taskA = factory(Task::class)->create([
            'employee_id' => $michael->id,
            'completed' => false,
        ]);
        $taskB = factory(Task::class)->create([
            'employee_id' => $michael->id,
            'completed' => false,
        ]);

        $response = DashboardMeViewHelper::tasks($michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $taskA->id,
                    'title' => $taskA->title,
                ],
                1 => [
                    'id' => $taskB->id,
                    'title' => $taskB->title,
                ],
            ],
            $response->toArray()
        );
    }
}
