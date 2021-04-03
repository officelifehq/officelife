<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\ImageHelper;
use Illuminate\Support\Collection;

class AdminAuditLogViewHelper
{
    public static function index($logs): Collection
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
                'localized_audited_at' => $log->date,
                'audited_at' => $log->audited_at,
            ]);
        }

        return $logsCollection;
    }
}
