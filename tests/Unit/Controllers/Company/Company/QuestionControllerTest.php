<?php

namespace Tests\Unit\Controllers\Company\Company;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Answer;
use App\Models\Company\Question;
use Illuminate\Testing\AssertableJsonString;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_get_the_detail_of_a_given_question(): void
    {
        $employee = $this->createAdministrator();
        $this->actingAs($employee->user);

        $question = factory(Question::class)->create([
            'company_id' => $employee->company_id,
        ]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);
        $answer = factory(Answer::class)->create([
            'question_id' => $question->id,
            'employee_id' => $employee->id,
        ]);

        $response = $this->get('/'.$employee->company_id.'/company/questions/'.$question->id.'/teams/'.$team->id);

        $response->assertStatus(200);

        $testJson = new AssertableJsonString($response->viewData('page')['props']);
        $testJson->assertFragment([
            'answer_by_employee' => [
                'id' => $answer->id,
                'body' => $answer->body,
                'employee_id' => config('database.connections.testing.driver') == 'mysql' ? (int) $employee->id : (string) $employee->id,
                'question_id' => config('database.connections.testing.driver') == 'mysql' ? (int) $question->id : (string) $question->id,
                'created_at' => $answer->created_at,
                'updated_at' => $answer->updated_at,
            ],
        ]);
    }
}
