<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectMessagesViewHelper;

class ProjectMessagesViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_messages(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessageA = ProjectMessage::factory()->create([
            'project_id' => $project->id,
            'author_id' => $michael->id,
        ]);
        $projectMessageB = ProjectMessage::factory()->create([
            'project_id' => $project->id,
            'author_id' => null,
        ]);

        DB::table('project_message_read_status')->insert([
            'project_message_id' => $projectMessageA->id,
            'employee_id' => $michael->id,
            'created_at' => Carbon::now(),
        ]);

        $collection = ProjectMessagesViewHelper::index($project, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectMessageA->id,
                    'title' => $projectMessageA->title,
                    'content' => $projectMessageA->content,
                    'read_status' => true,
                    'written_at' => $projectMessageA->created_at->diffForHumans(),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/messages/'.$projectMessageA->id,
                    'author' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael),
                        'url_view' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                ],
                1 => [
                    'id' => $projectMessageB->id,
                    'title' => $projectMessageB->title,
                    'content' => $projectMessageB->content,
                    'read_status' => false,
                    'written_at' => $projectMessageB->created_at->diffForHumans(),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/messages/'.$projectMessageB->id,
                    'author' => null,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_an_array_containing_all_the_information_about_a_given_message(): void
    {
        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessage = ProjectMessage::factory()->create([
            'project_id' => $project->id,
            'author_id' => $michael->id,
        ]);

        $array = ProjectMessagesViewHelper::show($projectMessage);
        $this->assertEquals(
            [
                'id' => $projectMessage->id,
                'title' => $projectMessage->title,
                'content' => $projectMessage->content,
                'parsed_content' => StringHelper::parse($projectMessage->content),
                'written_at' => DateHelper::formatDate($projectMessage->created_at),
                'written_at_human' => $projectMessage->created_at->diffForHumans(),
                'url_edit' => route('projects.messages.edit', [
                    'company' => $projectMessage->project->company_id,
                    'project' => $projectMessage->project,
                    'message' => $projectMessage,
                ]),
                'author' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael),
                    'role' => null,
                    'added_at' => null,
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array
        );
    }
}
