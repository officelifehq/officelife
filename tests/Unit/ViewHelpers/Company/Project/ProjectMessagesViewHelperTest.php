<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Comment;
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

        $comment = Comment::factory()->create();
        $projectMessageB->comments()->save($comment);

        DB::table('project_message_read_status')->insert([
            'project_message_id' => $projectMessageA->id,
            'employee_id' => $michael->id,
            'created_at' => Carbon::now(),
        ]);

        $collection = ProjectMessagesViewHelper::index($project, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectMessageB->id,
                    'title' => $projectMessageB->title,
                    'read_status' => false,
                    'comment_count' => 1,
                    'written_at' => $projectMessageB->created_at->diffForHumans(),
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/company/projects/' . $project->id . '/messages/' . $projectMessageB->id,
                    'author' => null,
                ],
                1 => [
                    'id' => $projectMessageA->id,
                    'title' => $projectMessageA->title,
                    'read_status' => true,
                    'comment_count' => 0,
                    'written_at' => $projectMessageA->created_at->diffForHumans(),
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/company/projects/' . $project->id . '/messages/' . $projectMessageA->id,
                    'author' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 22),
                        'url_view' => env('APP_URL') . '/' . $michael->company_id . '/employees/' . $michael->id,
                    ],
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
        $comment = Comment::factory()->create();
        $projectMessage->comments()->save($comment);

        $array = ProjectMessagesViewHelper::show($projectMessage, $michael);
        $this->assertEquals(
            $projectMessage->id,
            $array['id']
        );
        $this->assertEquals(
            $projectMessage->title,
            $array['title']
        );
        $this->assertEquals(
            $projectMessage->content,
            $array['content']
        );
        $this->assertEquals(
            StringHelper::parse($projectMessage->content),
            $array['parsed_content']
        );
        $this->assertEquals(
            DateHelper::formatDate($projectMessage->created_at),
            $array['written_at']
        );
        $this->assertEquals(
            $projectMessage->created_at->diffForHumans(),
            $array['written_at_human']
        );
        $this->assertEquals(
            route('projects.messages.edit', [
                'company' => $projectMessage->project->company_id,
                'project' => $projectMessage->project,
                'message' => $projectMessage,
            ]),
            $array['url_edit']
        );
        $this->assertEquals(
            [
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
            $array['author']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $comment->id,
                    'content' => StringHelper::parse($comment->content),
                    'content_raw' => $comment->content,
                    'written_at' => DateHelper::formatShortDateWithTime($comment->created_at),
                    'author' => [
                        'id' => $comment->author->id,
                        'name' => $comment->author->name,
                        'avatar' => ImageHelper::getAvatar($comment->author, 32),
                        'url' => route('employees.show', [
                            'company' => $projectMessage->project->company_id,
                            'employee' => $comment->author,
                        ]),
                    ],
                    'can_edit' => true,
                    'can_delete' => true,
                ],
            ],
            $array['comments']->toArray()
        );
    }
}
