<?php

namespace Tests\Unit\ViewHelpers\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Project;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Project\ProjectMessagesViewHelper;

class ProjectMessagesViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_collection_of_messages(): void
    {
        $michael = $this->createAdministrator();
        $jim = $this->createAnotherEmployee($michael);
        $project = factory(Project::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $projectMessageA = factory(ProjectMessage::class)->create([
            'project_id' => $project->id,
            'author_id' => $michael->id,
        ]);
        $projectMessageB = factory(ProjectMessage::class)->create([
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
                    'content' => 'This is a description',
                    'read_status' => true,
                    'written_at' => $projectMessageA->created_at->diffForHumans(),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/projects/'.$project->id.'/messages/'.$projectMessageA->id,
                    'author' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => $michael->avatar,
                        'url_view' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                ],
                1 => [
                    'id' => $projectMessageB->id,
                    'title' => $projectMessageB->title,
                    'content' => 'This is a description',
                    'read_status' => false,
                    'written_at' => $projectMessageB->created_at->diffForHumans(),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/projects/'.$project->id.'/messages/'.$projectMessageB->id,
                    'author' => null,
                ],
            ],
            $collection->toArray()
        );
    }
}
