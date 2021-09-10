<?php

namespace App\Http\ViewHelpers\Company\Project;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectMessage;

class ProjectMessagesViewHelper
{
    /**
     * Array containing the information about the messages made in the project.
     *
     * @param Project $project
     * @param Employee $employee
     * @return Collection
     */
    public static function index(Project $project, Employee $employee): Collection
    {
        $company = $project->company;
        $messages = $project->messages()
            ->select('id', 'title', 'content', 'created_at', 'author_id')
            ->with('author')
            ->with('comments')
            ->orderBy('id', 'desc')
            ->get();

        $messageReadStatuses = DB::table('project_message_read_status')
            ->whereIn('project_message_id', $messages->pluck('id'))
            ->get();

        $messagesCollection = collect([]);
        foreach ($messages as $message) {
            // check read status for each message
            $readStatus = $messageReadStatuses->contains(function ($readStatus, $key) use ($message, $employee) {
                return $readStatus->project_message_id == $message->id && $readStatus->employee_id == $employee->id;
            });

            $author = $message->author;

            $commentCount = $message->comments->count();

            $messagesCollection->push([
                'id' => $message->id,
                'title' => $message->title,
                'read_status' => $readStatus,
                'written_at' => $message->created_at->diffForHumans(),
                'comment_count' => $commentCount,
                'url' => route('projects.messages.show', [
                    'company' => $company,
                    'project' => $project,
                    'message' => $message,
                ]),
                'author' => $author ? [
                    'id' => $author->id,
                    'name' => $author->name,
                    'avatar' => ImageHelper::getAvatar($author, 22),
                    'url_view' => route('employees.show', [
                        'company' => $company,
                        'employee' => $author,
                    ]),
                ] : null,
            ]);
        }

        return $messagesCollection;
    }

    /**
     * Array containing the information about a given message.
     *
     * @param ProjectMessage $projectMessage
     * @param Employee $employee
     * @return array
     */
    public static function show(ProjectMessage $projectMessage, Employee $employee): array
    {
        // check author role in project
        $author = $projectMessage->author;
        $role = null;
        if ($author) {
            $role = DB::table('employee_project')
                ->where('employee_id', $author->id)
                ->where('project_id', $projectMessage->project->id)
                ->select('role', 'created_at')
                ->first();
        }

        // get comments
        $comments = $projectMessage->comments()->orderBy('created_at', 'asc')->get();
        $commentsCollection = collect([]);
        foreach ($comments as $comment) {
            $canDoActionsAgainstComment = false;

            if ($comment->author_id == $employee->id) {
                $canDoActionsAgainstComment = true;
            }
            if ($employee->permission_level <= config('officelife.permission_level.hr')) {
                $canDoActionsAgainstComment = true;
            }

            $commentsCollection->push([
                'id' => $comment->id,
                'content' => StringHelper::parse($comment->content),
                'content_raw' => $comment->content,
                'written_at' => DateHelper::formatShortDateWithTime($comment->created_at),
                'author' => $comment->author ? [
                    'id' => $comment->author->id,
                    'name' => $comment->author->name,
                    'avatar' => ImageHelper::getAvatar($comment->author, 32),
                    'url' => route('employees.show', [
                        'company' => $projectMessage->project->company_id,
                        'employee' => $comment->author,
                    ]),
                ] : $comment->author_name,
                'can_edit' => $canDoActionsAgainstComment,
                'can_delete' => $canDoActionsAgainstComment,
            ]);
        }

        return [
            'id' => $projectMessage->id,
            'title' => $projectMessage->title,
            'content' => $projectMessage->content,
            'parsed_content' => StringHelper::parse($projectMessage->content),
            'written_at' => DateHelper::formatDate($projectMessage->created_at, $employee->timezone),
            'written_at_human' => $projectMessage->created_at->diffForHumans(),
            'url_edit' => route('projects.messages.edit', [
                'company' => $projectMessage->project->company_id,
                'project' => $projectMessage->project,
                'message' => $projectMessage,
            ]),
            'author' => $author ? [
                'id' => $author->id,
                'name' => $author->name,
                'avatar' => ImageHelper::getAvatar($author, 64),
                'role' => $role ? $role->role : null,
                'added_at' => $role ? DateHelper::formatDate(Carbon::createFromFormat('Y-m-d H:i:s', $role->created_at), $employee->timezone) : null,
                'position' => (! $author->position) ? null : [
                    'id' => $author->position->id,
                    'title' => $author->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $projectMessage->project->company_id,
                    'employee' => $author,
                ]),
            ] : null,
            'comments' => $commentsCollection,
        ];
    }

    /**
     * Array containing the information necessary to edit a message.
     *
     * @param ProjectMessage $projectMessage
     * @return array
     */
    public static function edit(ProjectMessage $projectMessage): array
    {
        return [
            'id' => $projectMessage->id,
            'title' => $projectMessage->title,
            'content' => $projectMessage->content,
        ];
    }
}
