<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Company\Team;

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
     * @param Carbon $date
     * @return array
     */
    public static function getInformationAboutTeam(Team $team, Carbon $date) : array
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
            'date' => DateHelper::getLongDayAndMonth($date),
            'friendlyDate' => $date->format('Y-m-d'),
            'status' => $date->isFuture() == 1 ? 'future' : ($date->isCurrentDay() == 1 ? 'current' : 'past'),
            'completionRate' => $indicator,
            'numberOfEmployeesInTeam' => $numberOfEmployeesInTeam,
            'numberOfEmployeesWhoHaveLoggedWorklogs' => $numberOfEmployeesWhoHaveLoggedWorklogs,
        ];

        return $data;
    }
}
