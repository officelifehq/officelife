<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use App\Helpers\DateHelper;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamController extends Controller
{
    /**
     * Display the list of teams.
     *
     * @param int $companyId
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();

        $teams = $company->teams()
            ->with('employees')
            ->orderBy('name', 'asc')
            ->get();

        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'employees' => $team->employees,
            ]);
        }

        return Inertia::render('Team/Index', [
            'teams' => $teamsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the detail of a team.
     *
     * @param int $companyId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $companyId, $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $team = Team::where('company_id', $companyId)
                ->with('leader')
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // employees
        $employees = $team->employees()->orderBy('created_at', 'desc')->get();
        $employeesCollection = collect([]);
        foreach ($team->employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'position' => (!$employee->position) ? null : [
                    'id' => $employee->position->id,
                    'title' => $employee->position->title,
                ],
            ]);
        }

        // news
        $news = $team->news()->with('author')->orderBy('created_at', 'desc')->get();
        $newsCollection = collect([]);
        foreach ($news->take(3) as $newsItem) {
            $author = $newsItem->author;

            $newsCollection->push([
                'title' => $newsItem->title,
                'content' => $newsItem->content,
                'parsed_content' => StringHelper::parse($newsItem->content),
                'author' => [
                    'id' => is_null($author) ? null : $author->id,
                    'name' => is_null($author) ? null : $author->name,
                    'avatar' => is_null($author) ? null : $author->avatar,
                ],
                'localized_created_at' => DateHelper::formatShortDateWithTime($newsItem->created_at),
            ]);
        }

        // does the current logged user belongs to the team?
        // this is useful to know whether the user can do actions because he's part of the team
        $result = $employeesCollection->filter(function ($employee) use ($loggedEmployee) {
            return $employee['id'] === $loggedEmployee->id;
        });

        return Inertia::render('Team/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'team' => $team->toObject(),
            'news' => $newsCollection,
            'newsCount' => $news->count(),
            'employeeCount' => $employees->count(),
            'employees' => $employeesCollection,
            'userBelongsToTheTeam' => $result->count() > 0,
        ]);
    }
}
