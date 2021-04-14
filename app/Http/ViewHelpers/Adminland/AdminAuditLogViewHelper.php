<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class AdminAuditLogViewHelper
{
    public static function index($logs, Employee $loggedEmployee): Collection
    {
        $logsCollection = collect([]);
        foreach ($logs as $log) {
            $author = $log->author;

            $logsCollection->push([
                'id' => $log->id,
                'action' => $log->action,
                'objects' => json_decode($log->objects),
                'localized_content' => $log->content,
                'author' => [
                    'id' => is_null($author) ? null : $author->id,
                    'name' => is_null($author) ? $log->author_name : $author->name,
                    'avatar' => is_null($author) ? null : ImageHelper::getAvatar($author, 35),
                    'url' => is_null($author) ? null : route('employees.show', [
                        'company' => $author->company_id,
                        'employee' => $author,
                    ]),
                ],
                'localized_audited_at' => DateHelper::formatShortDateWithTime($log->audited_at, $loggedEmployee->timezone),
            ]);
        }

        return $logsCollection;
    }
}
