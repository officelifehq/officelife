<?php

namespace App\Http\Controllers\Company\Team;

use Inertia\Inertia;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\TeamNewsCollection;
use App\Http\Collections\TeamUsefulLinkCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TeamController extends Controller
{
    /**
     * Display the list of teams.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
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
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return Response
     */
    public function show(Request $request, int $companyId, int $teamId)
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
        $employees = $team->employees()->orderBy('employee_team.created_at', 'desc')->get();
        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'position' => (! $employee->position) ? null : [
                    'id' => $employee->position->id,
                    'title' => $employee->position->title,
                ],
            ]);
        }

        // news
        $news = $team->news()->with('author')->orderBy('created_at', 'desc')->get();
        $newsCollection = TeamNewsCollection::prepare($news->take(3));

        // does the current logged user belongs to the team?
        // this is useful to know whether the user can do actions because he's part of the team
        $result = $employeesCollection->filter(function ($employee) use ($loggedEmployee) {
            return $employee['id'] === $loggedEmployee->id;
        });

        // most recent member
        $mostRecentMember = trans('team.most_recent_team_member', [
            'count' => $employees->count(),
            'link' => ($employees->count() > 0) ? $employees->take(1)->first()->name : '',
        ]);

        return Inertia::render('Team/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'team' => $team->toObject(),
            'news' => $newsCollection,
            'newsCount' => $news->count(),
            'mostRecentEmployee' => $mostRecentMember,
            'employeeCount' => $employees->count(),
            'employees' => $employeesCollection,
            'userBelongsToTheTeam' => $result->count() > 0,
            'links' => TeamUsefulLinkCollection::prepare($team->links),
        ]);
    }
}
