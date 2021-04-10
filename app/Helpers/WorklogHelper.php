<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;

class WorklogHelper
{
    /**
     * Prepares an array containing all the information regarding the worklogs
     * logged on the given day, along with the morale (if logged).
     *
     * @param Carbon $date
     * @param Worklog|null $worklog
     * @param Morale|null $morale
     * @param Employee $loggedEmployee
     * @return array
     */
    public static function getDailyInformationForEmployee(Carbon $date, Worklog $worklog = null, Morale $morale = null, Employee $loggedEmployee): array
    {
        return [
            'date' => DateHelper::formatShortDateWithTime($date, $loggedEmployee->timezone),
            'friendly_date' => DateHelper::formatDayAndMonthInParenthesis($date),
            'status' => DateHelper::determineDateStatus($date),
            'worklog_parsed_content' => is_null($worklog) ? null : StringHelper::parse($worklog->content),
            'morale' => is_null($morale) ? null : $morale->emoji,
        ];
    }
}
