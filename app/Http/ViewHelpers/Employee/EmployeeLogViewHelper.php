<?php

namespace App\Http\ViewHelpers\Employee;

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
            $logsCollection->push([
                'localized_content' => $log->content,
                'author' => [
                    'id' => is_null($log->author) ? null : $log->author->id,
                    'name' => is_null($log->author) ? $log->author_name : $log->author->name,
                    'avatar' => is_null($log->author) ? null : $log->author->avatar,
                    'url' => is_null($log->author) ? null : route('employees.show', [
                        'company' => $company,
                        'employee' => $log->author,
                    ]),
                ],
                'localized_audited_at' => $log->date,
            ]);
        }

        return $logsCollection;
    }
}
