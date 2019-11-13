<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Company\Team;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;

class WorklogHelper
{
    /**
     * Creates an array containing all the information regarding the worklogs
     * logged on a specific day for a specific team.
     * Used on the team page and the team dashboard page.
     *
     * This array also contains an indicator telling how many team members have
     * filled the worklogs for the day. The rules are as follow:
     * - less than 20% of team members have filled the worklogs: red
     * - 20% -> 80%: yellow
     * - > 80%: green
     *
     * @param Team $team
     * @param Carbon $date
     * @return array
     */
    public static function getInformationAboutTeam(Team $team, Carbon $date): array
    {
        $numberOfEmployeesInTeam = $team->employees()->count();
        $numberOfEmployeesWhoHaveLoggedWorklogs = count($team->worklogsForDate($date));
        $percent = $numberOfEmployeesWhoHaveLoggedWorklogs * 100 / $numberOfEmployeesInTeam;

        $indicator = 'red';

        if ($percent >= 20 && $percent <= 80) {
            $indicator = 'yellow';
        }

        if ($percent > 80) {
            $indicator = 'green';
        }

        $data = [
            'day' => $date->isoFormat('dddd'),
            'date' => DateHelper::getMonthAndDay($date),
            'friendlyDate' => $date->format('Y-m-d'),
            'status' => $date->isFuture() == 1 ? 'future' : ($date->isCurrentDay() == 1 ? 'current' : 'past'),
            'completionRate' => $indicator,
            'numberOfEmployeesInTeam' => $numberOfEmployeesInTeam,
            'numberOfEmployeesWhoHaveLoggedWorklogs' => $numberOfEmployeesWhoHaveLoggedWorklogs,
        ];

        return $data;
    }

    /**
     * Creates an array containing all the information regarding the worklogs
     * logged on a specific day for a specific employee.
     * Used on the Employee page.
     *
     * @param Employee $employee
     * @param Carbon $date
     * @return array
     */
    public static function getInformation(Employee $employee, Carbon $date): array
    {
        $morale = Morale::where('employee_id', $employee->id)
            ->whereDate('created_at', $date)
            ->first();

        $worklog = Worklog::where('employee_id', $employee->id)
            ->whereDate('created_at', $date)
            ->first();

        $data = [
            'date' => DateHelper::getShortDateWithTime($date),
            'friendly_date' => DateHelper::getDayAndMonthInParenthesis($date),
            'status' => $date->isFuture() == 1 ? 'future' : ($date->isCurrentDay() == 1 ? 'current' : 'past'),
            'worklog_parsed_content' => is_null($worklog) ? null : StringHelper::parse($worklog->content),
            'morale' => is_null($morale) ? null : $morale->emoji,
        ];

        return $data;
    }
}
