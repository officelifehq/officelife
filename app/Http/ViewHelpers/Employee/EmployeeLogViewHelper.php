<?php

namespace App\Http\ViewHelpers\Employee;

use App\Helpers\AvatarHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class EmployeeLogViewHelper
{
    /**
     * Collection containing all the audit log entries for this employee.
     * @param mixed $logs
     */
    public static function list($logs, Company $company): Collection
    {
        $logsCollection = collect([]);
        foreach ($logs as $log) {
            $author = $log->author;

            $logsCollection->push([
                'localized_content' => $log->content,
                'author' => [
                    'id' => is_null($author) ? null : $author->id,
                    'name' => is_null($author) ? $log->author_name : $author->name,
                    'avatar' => is_null($author) ? null : AvatarHelper::getImage($author),
                    'url' => is_null($author) ? null : route('employees.show', [
                        'company' => $company,
                        'employee' => $author,
                    ]),
                ],
                'localized_audited_at' => $log->date,
            ]);
        }

        return $logsCollection;
    }
}
