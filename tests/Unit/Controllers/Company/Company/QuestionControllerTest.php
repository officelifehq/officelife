<?php

namespace Tests\Unit\Controllers\Company\Company;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Answer;
use App\Models\Company\Question;
use Illuminate\Support\Facades\DB;
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

        $question = Question::factory()->create([
            'company_id' => $employee->company_id,
        ]);
        $team = Team::factory()->create([
            'company_id' => $employee->company_id,
        ]);
        $answer = Answer::factory()->create([
            'question_id' => $question->id,
            'employee_id' => $employee->id,
        ]);

        $response = $this->get('/'.$employee->company_id.'/company/questions/'.$question->id.'/teams/'.$team->id);

        $response->assertStatus(200);

        /** @var \Illuminate\Database\Connection */
        $connection = DB::connection();

        $testJson = new AssertableJsonString($response->viewData('page')['props']);
        $testJson->assertFragment([
            'answer_by_employee' => [
                'id' => $answer->id,
                'body' => $answer->body,
                'employee_id' => $connection->getDriverName() == 'sqlite' ? (string) $employee->id : (int) $employee->id,
                'question_id' => $connection->getDriverName() == 'sqlite' ? (string) $question->id : (int) $question->id,
                'created_at' => $answer->created_at,
                'updated_at' => $answer->updated_at,
            ],
        ]);
    }
}
