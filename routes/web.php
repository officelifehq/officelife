<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;

Route::get('/', function () {
    return Redirect::route('login');
})->name('default');

Route::get('invite/employee/{link}', 'Auth\\UserInvitationController@check');
Route::post('invite/employee/{link}/join', 'Auth\\UserInvitationController@join')->name('invitation.join');

Route::get('auth/{driver}', 'Auth\SocialiteCallbackController@login')->name('login.provider');
Route::get('auth/{driver}/callback', 'Auth\SocialiteCallbackController@callback');
Route::post('auth/{driver}/callback', 'Auth\SocialiteCallbackController@callback');

// jobs public section
Route::prefix('jobs')->group(function () {
    Route::get('', 'Jobs\\JobsController@index')->name('jobs');
    Route::get('{company}', 'Jobs\\JobsCompanyController@index')->name('jobs.company.index');
    Route::get('{company}/jobs/{job}', 'Jobs\\JobsCompanyController@show')->name('jobs.company.show');
    Route::get('{company}/jobs/{job}?ignore=true', 'Jobs\\JobsCompanyController@show')->name('jobs.company.show.incognito');
    Route::get('{company}/jobs/{job}/apply', 'Jobs\\JobsCompanyController@apply')->name('jobs.company.apply');
    Route::post('{company}/jobs/{job}', 'Jobs\\JobsCompanyController@store');
    Route::get('{company}/jobs/{job}/apply/{candidate}/cv', 'Jobs\\JobsCompanyController@cv')->name('jobs.company.cv');
    Route::post('{company}/jobs/{job}/apply/{candidate}/cv', 'Jobs\\JobsCompanyController@storeCv');
    Route::post('{company}/jobs/{job}/apply/{candidate}', 'Jobs\\JobsCompanyController@finalizeApplication');
    Route::get('{company}/jobs/{job}/apply/{candidate}/success', 'Jobs\\JobsCompanyController@success')->name('jobs.company.success');
    Route::delete('{company}/jobs/{job}/apply/{candidate}/cv/{file}', 'Jobs\\JobsCompanyController@destroyCv');
    Route::delete('{company}/jobs/{job}/apply/{candidate}', 'Jobs\\JobsCompanyController@destroy');
});

// logged app
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('companies', 'HomeController@list')->name('companies');
    Route::post('search/employees', 'HeaderSearchController@employees');
    Route::post('search/teams', 'HeaderSearchController@teams');

    Route::post('help', 'HelpController@toggle');
    Route::post('locale', 'User\\LocaleController@update');

    Route::get('company/create', 'Company\\CompanyController@create');
    Route::post('company/store', 'Company\\CompanyController@store')->name('company.store');
    Route::get('company/join', 'Company\\CompanyController@join');
    Route::post('company/join', 'Company\\CompanyController@actuallyJoin')->name('company.join');

    // only available if user is in the right account
    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::get('welcome', 'WelcomeController@index')->name('welcome');
        Route::post('hide', 'WelcomeController@hide');

        Route::get('notifications', 'User\\Notification\\NotificationController@index');
        Route::post('notifications/read', 'User\\Notification\\MarkNotificationAsReadController@store');

        // get the list of the pronouns in the company
        Route::get('pronouns', 'Company\\Company\\PronounController@index');

        // get the list of the positions in the company
        Route::get('positions', 'Company\\Company\\PositionController@index');

        // get the issue - an issue should have the shortest link possible
        Route::get('issues/{key}/{slug}', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssuesController@show')->name('projects.issues.show');

        Route::prefix('dashboard')->group(function () {
            Route::get('', 'Company\\Dashboard\\DashboardController@index')->name('dashboard');

            // me
            Route::get('me', 'Company\\Dashboard\\Me\\DashboardMeController@index')->name('dashboard.me');

            Route::post('worklog', 'Company\\Dashboard\\Me\\DashboardWorklogController@store');
            Route::post('morale', 'Company\\Dashboard\\Me\\DashboardMoraleController@store');
            Route::post('workFromHome', 'Company\\Dashboard\\Me\\DashboardWorkFromHomeController@store');
            Route::resource('question', 'Company\\Dashboard\\Me\\DashboardQuestionController')->only([
                'store', 'update', 'destroy',
            ]);
            Route::post('expense', 'Company\\Dashboard\\Me\\DashboardMeExpenseController@store')->name('dashboard.expense.store');

            // add note as participant of a job opening recruitment process
            Route::post('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/notes', 'Company\\Dashboard\\Me\\DashboardMeRecruitingController@store');

            // details of one on ones
            Route::get('oneonones/{entry}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@show')->name('dashboard.oneonones.show');
            Route::post('oneonones/{entry}/happened', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@markHappened');

            Route::post('oneonones/{entry}/talkingPoints', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@storeTalkingPoint');
            Route::post('oneonones/{entry}/talkingPoints/{talkingPoint}/toggle', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@toggleTalkingPoint');
            Route::post('oneonones/{entry}/talkingPoints/{talkingPoint}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@updateTalkingPoint');
            Route::delete('oneonones/{entry}/talkingPoints/{talkingPoint}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@destroyTalkingPoint');

            Route::post('oneonones/{entry}/actionItems', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@storeActionItem');
            Route::post('oneonones/{entry}/actionItems/{actionItem}/toggle', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@toggleActionItem');
            Route::post('oneonones/{entry}/actionItems/{actionItem}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@updateActionItem');
            Route::delete('oneonones/{entry}/actionItems/{actionItem}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@destroyActionItem');

            Route::post('oneonones/{entry}/notes', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@storeNote');
            Route::post('oneonones/{entry}/notes/{note}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@updateNote');
            Route::delete('oneonones/{entry}/notes/{note}', 'Company\\Dashboard\\Me\\DashboardMeOneOnOneController@destroyNote');

            // rate your manager
            Route::post('rate/{answer}', 'Company\\Dashboard\\Me\\DashboardRateYourManagerController@store');
            Route::post('rate/{answer}/comment', 'Company\\Dashboard\\Me\\DashboardRateYourManagerController@storeComment');

            // ecoffee
            Route::post('ecoffee/{ecoffee}/{match}', 'Company\\Dashboard\\Me\\DashboardMeECoffeeController@store');

            // timesheets
            Route::get('timesheet/projects', 'Company\\Dashboard\\Timesheets\\DashboardTimesheetController@projects')->name('dashboard.timesheet.projects');
            Route::get('timesheet/{timesheet}/projects/{project}/tasks', 'Company\\Dashboard\\Timesheets\\DashboardTimesheetController@tasks')->name('dashboard.timesheet.tasks');
            Route::resource('timesheet', 'Company\\Dashboard\\Timesheets\\DashboardTimesheetController', ['as' => 'dashboard'])->only([
                'index', 'show', 'destroy',
            ]);
            Route::post('timesheet/{timesheet}/store', 'Company\\Dashboard\\Timesheets\\DashboardTimesheetController@createTimeTrackingEntry')->name('dashboard.timesheet.entry.store');
            Route::post('timesheet/{timesheet}/submit', 'Company\\Dashboard\\Timesheets\\DashboardTimesheetController@submit')->name('dashboard.timesheet.entry.submit');
            Route::put('timesheet/{timesheet}/row', 'Company\\Dashboard\\Timesheets\\DashboardTimesheetController@destroyRow');

            // team
            Route::get('team', 'Company\\Dashboard\\Teams\\DashboardTeamController@index')->name('dashboard.team');
            Route::get('team/{team}', 'Company\\Dashboard\\Teams\\DashboardTeamController@index');
            Route::get('team/{team}/{date}', 'Company\\Dashboard\\Teams\\DashboardTeamController@worklogDetails');
            Route::delete('team/{team}/{worklog}/{employee}', 'Company\\Dashboard\\Teams\\DashboardTeamController@destroyWorkLog');

            // manager tab
            Route::prefix('manager')->group(function () {
                Route::get('', 'Company\\Dashboard\\Manager\\DashboardManagerController@index')->name('dashboard.manager');
                Route::get('expenses/{expense}', 'Company\\Dashboard\\Manager\\DashboardManagerController@showExpense')->name('dashboard.manager.expense.show');
                Route::post('expenses/{expense}/accept', 'Company\\Dashboard\\Manager\\DashboardManagerController@accept');
                Route::post('expenses/{expense}/reject', 'Company\\Dashboard\\Manager\\DashboardManagerController@reject');

                // timesheets
                Route::get('timesheets', 'Company\\Dashboard\\Manager\\DashboardManagerTimesheetController@index')->name('dashboard.manager.timesheet.index');
                Route::get('timesheets/{timesheet}', 'Company\\Dashboard\\Manager\\DashboardManagerTimesheetController@show')->name('dashboard.manager.timesheet.show');
                Route::post('timesheets/{timesheet}/approve', 'Company\\Dashboard\\Manager\\DashboardManagerTimesheetController@approve');
                Route::post('timesheets/{timesheet}/reject', 'Company\\Dashboard\\Manager\\DashboardManagerTimesheetController@reject');

                // discipline cases
                Route::get('discipline-cases/{case}', 'Company\\Dashboard\\Manager\\DashboardManagerDisciplineCaseController@show')->name('dashboard.manager.disciplinecase.show');
            });

            // hr tab
            Route::prefix('hr')->middleware(['hr'])->group(function () {
                Route::get('', 'Company\\Dashboard\\HR\\DashboardHRController@index')->name('dashboard.hr');

                // timesheets
                Route::get('timesheets', 'Company\\Dashboard\\HR\\DashboardHRTimesheetController@index')->name('dashboard.hr.timesheet.index');
                Route::get('timesheets/{timesheet}', 'Company\\Dashboard\\HR\\DashboardHRTimesheetController@show')->name('dashboard.hr.timesheet.show');
                Route::post('timesheets/{timesheet}/approve', 'Company\\Dashboard\\HR\\DashboardHRTimesheetController@approve');
                Route::post('timesheets/{timesheet}/reject', 'Company\\Dashboard\\HR\\DashboardHRTimesheetController@reject');

                // discipline cases
                Route::get('discipline-cases', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@index')->name('dashboard.hr.disciplinecase.index');
                Route::post('discipline-cases', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@store')->name('dashboard.hr.disciplinecase.store');
                Route::get('discipline-cases/closed', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@closed')->name('dashboard.hr.disciplinecase.index.closed');
                Route::post('discipline-cases/employees', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@search')->name('dashboard.hr.disciplinecase.search.employees');
                Route::get('discipline-cases/{case}', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@show')->name('dashboard.hr.disciplinecase.show');
                Route::put('discipline-cases/{case}', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@toggle')->name('dashboard.hr.disciplinecase.toggle');
                Route::delete('discipline-cases/{case}', 'Company\\Dashboard\\HR\\DashboardDisciplineCasesController@destroy')->name('dashboard.hr.disciplinecase.destroy');

                // discipline events
                Route::post('discipline-cases/{case}/events', 'Company\\Dashboard\\HR\\DashboardDisciplineEventsController@store')->name('dashboard.hr.disciplineevent.store');
                Route::put('discipline-cases/{case}/events/{event}/update', 'Company\\Dashboard\\HR\\DashboardDisciplineEventsController@update')->name('dashboard.hr.disciplineevent.update');
                Route::delete('discipline-cases/{case}/events/{event}', 'Company\\Dashboard\\HR\\DashboardDisciplineEventsController@destroy')->name('dashboard.hr.disciplineevent.destroy');
            });
        });

        Route::prefix('employees')->group(function () {
            Route::get('', 'Company\\Employee\\EmployeeController@index')->name('employees.index');

            // common to all pages
            Route::resource('{employee}/team', 'Company\\Employee\\EmployeeTeamController')->only([
                'index', 'store', 'destroy',
            ]);
            Route::resource('{employee}/position', 'Company\\Employee\\EmployeePositionController')->only([
                'store', 'destroy',
            ]);
            Route::resource('{employee}/employeestatuses', 'Company\\Employee\\EmployeeStatusController')->only([
                'index', 'store', 'destroy',
            ]);
            Route::resource('{employee}/pronoun', 'Company\\Employee\\EmployeePronounController')->only([
                'store', 'destroy',
            ]);
            Route::resource('{employee}/description', 'Company\\Employee\\EmployeeDescriptionController')->only([
                'store', 'destroy',
            ]);

            // Presentation tab
            Route::get('{employee}', 'Company\\Employee\\Presentation\\EmployeePresentationController@show')->name('employees.show');
            Route::put('{employee}/assignManager', 'Company\\Employee\\Presentation\\EmployeePresentationController@assignManager')->name('employee.manager.assign');
            Route::put('{employee}/assignDirectReport', 'Company\\Employee\\Presentation\\EmployeePresentationController@assignDirectReport')->name('employee.directReport.assign');
            Route::post('{employee}/search/hierarchy', 'Company\\Employee\\Presentation\\EmployeeSearchController@hierarchy');
            Route::put('{employee}/unassignManager', 'Company\\Employee\\Presentation\\EmployeePresentationController@unassignManager')->name('employee.manager.unassign');
            Route::put('{employee}/unassignDirectReport', 'Company\\Employee\\Presentation\\EmployeePresentationController@unassignDirectReport')->name('employee.directReport.unassign');
            Route::resource('{employee}/skills', 'Company\\Employee\\Presentation\\EmployeeSkillController')->only([
                'store', 'destroy',
            ]);
            Route::post('{employee}/skills/search', 'Company\\Employee\\Presentation\\EmployeeSkillController@search')->name('skills.search');
            Route::get('{employee}/ecoffees', 'Company\\Employee\\Presentation\\eCoffee\\EmployeeECoffeeController@index')->name('employees.ecoffees.index');

            // Edit page
            Route::put('{employee}/avatar/update', 'Company\\Employee\\Edit\\EmployeeEditAvatarController@update');
            Route::get('{employee}/edit', 'Company\\Employee\\Edit\\EmployeeEditController@show')->name('employee.show.edit');
            Route::get('{employee}/address/edit', 'Company\\Employee\\Edit\\EmployeeEditController@address')->name('employee.show.edit.address');
            Route::get('{employee}/contract/edit', 'Company\\Employee\\Edit\\EmployeeEditController@contract')->name('employee.show.edit.contract');
            Route::post('{employee}/contract/update', 'Company\\Employee\\Edit\\EmployeeEditController@updateContractInformation');
            Route::post('{employee}/update', 'Company\\Employee\\Edit\\EmployeeEditController@update');
            Route::post('{employee}/address/update', 'Company\\Employee\\Edit\\EmployeeEditController@updateAddress');
            Route::post('{employee}/rate/store', 'Company\\Employee\\Edit\\EmployeeEditController@storeRate');
            Route::delete('{employee}/rate/{rate}', 'Company\\Employee\\Edit\\EmployeeEditController@destroyRate');

            Route::get('{employee}/logs', 'Company\\Employee\\EmployeeLogsController@index')->name('employee.show.logs');

            // administration tab
            Route::prefix('{employee}/administration')->group(function () {
                Route::middleware(['employeeOrManagerOrAtLeastHR'])->group(function () {
                    Route::get('', 'Company\\Employee\\Administration\\EmployeeAdministrationController@show')->name('employees.administration.show');

                    // expenses
                    Route::get('expenses', 'Company\\Employee\\Administration\\Expenses\\EmployeeExpenseController@index')->name('employee.administration.expenses.index');
                    Route::get('expenses/{expense}', 'Company\\Employee\\Administration\\Expenses\\EmployeeExpenseController@show')->name('employee.administration.expenses.show');
                    Route::delete('expenses/{expense}', 'Company\\Employee\\Administration\\Expenses\\EmployeeExpenseController@destroy');

                    // timesheets
                    Route::get('timesheets', 'Company\\Employee\\Administration\\Timesheets\\EmployeeTimesheetController@index')->name('employee.timesheets.index');
                    Route::get('timesheets/{timesheet}', 'Company\\Employee\\Administration\\Timesheets\\EmployeeTimesheetController@show')->name('employee.timesheets.show');
                    Route::get('timesheets/overview/{year}', 'Company\\Employee\\Administration\\Timesheets\\EmployeeTimesheetController@year')->name('employee.timesheets.year');
                    Route::get('timesheets/overview/{year}/{month}', 'Company\\Employee\\Administration\\Timesheets\\EmployeeTimesheetController@month')->name('employee.timesheets.month');
                });
            });

            // work tab
            Route::prefix('{employee}/work')->group(function () {
                Route::get('', 'Company\\Employee\\Work\\EmployeeWorkController@show')->name('employees.show.work');

                // work from home
                Route::get('workfromhome', 'Company\\Employee\\Work\\WorkFromHome\\EmployeeWorkFromHomeController@index')->name('employee.work.workfromhome');
                Route::get('workfromhome/{year}', 'Company\\Employee\\Work\\WorkFromHome\\EmployeeWorkFromHomeController@year');
                Route::get('workfromhome/{year}/{month}', 'Company\\Employee\\Work\\WorkFromHome\\EmployeeWorkFromHomeController@month');

                // worklogs
                Route::get('worklogs/week/{week}/day/{day}', 'Company\\Employee\\Work\\EmployeeWorkController@worklogDay');
                Route::get('worklogs/week/{week}/day', 'Company\\Employee\\Work\\EmployeeWorkController@worklogDay');
                Route::delete('worklogs/{worklog}', 'Company\\Employee\\Work\\EmployeeWorkController@destroyWorkLog');
            });

            // performance tab
            Route::prefix('{employee}/performance')->group(function () {
                Route::get('', 'Company\\Employee\\Performance\\EmployeePerformanceController@show')->name('employees.show.performance');

                // survey
                Route::get('surveys', 'Company\\Employee\\Performance\\Surveys\\EmployeeSurveysController@index')->name('employees.show.performance.survey.index');
                Route::get('/surveys/{survey}', 'Company\\Employee\\Performance\\Surveys\\EmployeeSurveysController@show')->name('employees.show.performance.survey.show');

                // one on ones
                Route::get('oneonones', 'Company\\Employee\\Performance\\OneOnOnes\\EmployeeOneOnOneController@index')->name('employees.show.performance.oneonones.index');
                Route::get('oneonones/{oneonone}', 'Company\\Employee\\Performance\\OneOnOnes\\EmployeeOneOnOneController@show')->name('employees.show.performance.oneonones.show');
            });
        });

        Route::prefix('teams')->group(function () {
            Route::get('', 'Company\\Team\\TeamController@index')->name('teams.index');
            Route::get('{team}', 'Company\\Team\\TeamController@show')->name('team.show');

            Route::post('{team}/members/search', 'Company\\Team\\TeamMembersController@index');
            Route::post('{team}/members/attach/{employee}', 'Company\\Team\\TeamMembersController@attach');
            Route::post('{team}/members/detach/{employee}', 'Company\\Team\\TeamMembersController@detach');

            Route::resource('{team}/description', 'Company\\Team\\TeamDescriptionController', ['as' => 'description'])->only([
                'store', 'destroy',
            ]);

            Route::resource('{team}/lead', 'Company\\Team\\TeamLeadController')->only([
                'store', 'destroy',
            ]);
            Route::post('{team}/lead/search', 'Company\\Team\\TeamLeadController@search');

            Route::resource('{team}/news', 'Company\\Team\\TeamNewsController');

            Route::resource('{team}/links', 'Company\\Team\\TeamUsefulLinkController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{team}/ships', 'Company\\Team\\TeamRecentShipController');
            Route::post('{team}/ships/search', 'Company\\Team\\TeamRecentShipController@search');
        });

        Route::prefix('company')->group(function () {
            Route::get('', 'Company\\Company\\CompanyController@index')->name('company.index');
            Route::post('guessEmployee/vote', 'Company\\Company\\CompanyController@vote');
            Route::get('guessEmployee/replay', 'Company\\Company\\CompanyController@replay');

            // Questions and answers
            Route::resource('questions', 'Company\\Company\\QuestionController', ['as' => 'company'])->only([
                'index', 'show',
            ]);
            Route::get('questions/{question}/teams/{team}', 'Company\\Company\\QuestionController@team');

            // Company news
            Route::resource('news', 'Company\\Company\\CompanyNewsController', ['as' => 'company'])->only([
                'index', 'show',
            ]);

            // Skills
            Route::get('skills', 'Company\\Company\\SkillController@index')->name('company.skills.index');
            Route::get('skills/{skill}', 'Company\\Company\\SkillController@show')->name('company.skills.show');
            Route::put('skills/{skill}', 'Company\\Company\\SkillController@update');
            Route::delete('skills/{skill}', 'Company\\Company\\SkillController@destroy');

            // Projects
            Route::prefix('projects')->group(function () {
                Route::get('', 'Company\\Company\\Project\\ProjectController@index')->name('projects.index');
                Route::get('create', 'Company\\Company\\Project\\ProjectController@create');
                Route::post('', 'Company\\Company\\Project\\ProjectController@store');
                Route::post('search', 'Company\\Company\\Project\\ProjectController@search');

                // project detail
                Route::middleware(['project'])->group(function () {
                    Route::get('{project}', 'Company\\Company\\Project\\ProjectController@show')->name('projects.show');
                    Route::get('{project}/summary', 'Company\\Company\\Project\\ProjectController@show');

                    Route::post('{project}/start', 'Company\\Company\\Project\\ProjectController@start');
                    Route::post('{project}/pause', 'Company\\Company\\Project\\ProjectController@pause');
                    Route::post('{project}/close', 'Company\\Company\\Project\\ProjectController@close');
                    Route::post('{project}/lead/assign', 'Company\\Company\\Project\\ProjectController@assign');
                    Route::post('{project}/lead/clear', 'Company\\Company\\Project\\ProjectController@clear');
                    Route::get('{project}/edit', 'Company\\Company\\Project\\ProjectController@edit')->name('projects.edit');
                    Route::post('{project}/description', 'Company\\Company\\Project\\ProjectController@description');
                    Route::post('{project}/update', 'Company\\Company\\Project\\ProjectController@update');
                    Route::get('{project}/delete', 'Company\\Company\\Project\\ProjectController@delete')->name('projects.delete');
                    Route::delete('{project}', 'Company\\Company\\Project\\ProjectController@destroy');

                    Route::post('{project}/links', 'Company\\Company\\Project\\ProjectController@createLink');
                    Route::delete('{project}/links/{link}', 'Company\\Company\\Project\\ProjectController@destroyLink');

                    Route::get('{project}/status', 'Company\\Company\\Project\\ProjectController@createStatus');
                    Route::put('{project}/status', 'Company\\Company\\Project\\ProjectController@postStatus');

                    // project decision logs
                    Route::get('{project}/decisions', 'Company\\Company\\Project\\ProjectDecisionsController@index');
                    Route::post('{project}/decisions/search', 'Company\\Company\\Project\\ProjectDecisionsController@search');
                    Route::post('{project}/decisions/store', 'Company\\Company\\Project\\ProjectDecisionsController@store');
                    Route::delete('{project}/decisions/{decision}', 'Company\\Company\\Project\\ProjectDecisionsController@destroy');

                    // project members
                    Route::get('{project}/members', 'Company\\Company\\Project\\ProjectMembersController@index');
                    Route::post('{project}/members/search', 'Company\\Company\\Project\\ProjectMembersController@search');
                    Route::post('{project}/members', 'Company\\Company\\Project\\ProjectMembersController@store');
                    Route::delete('{project}/members/{member}', 'Company\\Company\\Project\\ProjectMembersController@destroy');

                    // project messages
                    Route::resource('{project}/messages', 'Company\\Company\\Project\\ProjectMessagesController', ['as' => 'projects']);
                    Route::post('{project}/messages/{message}/comments', 'Company\\Company\\Project\\ProjectMessagesCommentController@store');
                    Route::put('{project}/messages/{message}/comments/{comment}', 'Company\\Company\\Project\\ProjectMessagesCommentController@update');
                    Route::delete('{project}/messages/{message}/comments/{comment}', 'Company\\Company\\Project\\ProjectMessagesCommentController@destroy');

                    // project tasks
                    Route::resource('{project}/tasks', 'Company\\Company\\Project\\ProjectTasksController', ['as' => 'projects']);
                    Route::put('{project}/tasks/{task}/toggle', 'Company\\Company\\Project\\ProjectTasksController@toggle')->name('projects.tasks.toggle');
                    Route::post('{project}/tasks/lists/store', 'Company\\Company\\Project\\ProjectTaskListsController@store');
                    Route::put('{project}/tasks/lists/{list}', 'Company\\Company\\Project\\ProjectTaskListsController@update');
                    Route::delete('{project}/tasks/lists/{list}', 'Company\\Company\\Project\\ProjectTaskListsController@destroy');
                    Route::get('{project}/tasks/{task}/timeTrackingEntries', 'Company\\Company\\Project\\ProjectTasksController@timeTrackingEntries')->name('projects.tasks.timeTrackingEntries');
                    Route::post('{project}/tasks/{task}/log', 'Company\\Company\\Project\\ProjectTasksController@logTime');
                    Route::post('{project}/tasks/{task}/comments', 'Company\\Company\\Project\\ProjectTasksCommentController@store');
                    Route::put('{project}/tasks/{task}/comments/{comment}', 'Company\\Company\\Project\\ProjectTasksCommentController@update');
                    Route::delete('{project}/tasks/{task}/comments/{comment}', 'Company\\Company\\Project\\ProjectTasksCommentController@destroy');

                    // files
                    Route::get('{project}/files', 'Company\\Company\\Project\\ProjectFilesController@index');
                    Route::post('{project}/files', 'Company\\Company\\Project\\ProjectFilesController@store');
                    Route::delete('{project}/files/{file}', 'Company\\Company\\Project\\ProjectFilesController@destroy');

                    // boards
                    Route::get('{project}/boards', 'Company\\Company\\Project\\ProjectBoardsController@index')->name('projects.boards.index');
                    Route::post('{project}/boards', 'Company\\Company\\Project\\ProjectBoardsController@store')->name('projects.boards.store');

                    Route::middleware(['board'])->prefix('{project}/boards')->group(function () {
                        Route::get('{board}', 'Company\\Company\\Project\\ProjectBoardsController@show')->name('projects.boards.show');
                        Route::put('{board}', 'Company\\Company\\Project\\ProjectBoardsController@update')->name('projects.boards.update');
                        Route::delete('{board}', 'Company\\Company\\Project\\ProjectBoardsController@destroy')->name('projects.boards.destroy');
                        Route::get('{board}/backlog', 'Company\\Company\\Project\\ProjectBoardsBacklogController@show')->name('projects.boards.show.backlog');
                        Route::post('{board}/sprints/{sprint}/start', 'Company\\Company\\Project\\ProjectSprintController@start')->name('projects.sprints.start');
                        Route::post('{board}/sprints/{sprint}/toggle', 'Company\\Company\\Project\\ProjectSprintController@toggle')->name('projects.sprints.toggle');

                        // issues
                        Route::post('{board}/sprints/{sprint}/issues', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssuesController@store')->name('projects.issues.store');
                        Route::post('{board}/sprints/{sprint}/issues/{issue}/order', 'Company\\Company\\Project\\ProjectSprintController@storePosition')->name('projects.issues.store.order');
                        Route::get('{board}/members', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssueAssigneesController@index')->name('projects.members');
                        Route::post('{board}/issues/{issue}/assignees', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssueAssigneesController@store')->name('projects.issues.assignees.store');
                        Route::post('{board}/issues/{issue}/points', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssuePointsController@store')->name('projects.issues.points.store');
                        Route::delete('{board}/issues/{issue}/assignees/{assignee}', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssueAssigneesController@destroy')->name('projects.issues.assignees.destroy');
                        Route::delete('{board}/sprints/{sprint}/issues/{issue}', 'Company\\Company\\Project\\ProjectIssue\\ProjectIssuesController@destroy')->name('projects.issues.destroy');
                    });
                });
            });

            // Groups
            Route::prefix('groups')->group(function () {
                Route::get('', 'Company\\Company\\Group\\GroupController@index');
                Route::get('create', 'Company\\Company\\Group\\GroupController@create')->name('groups.new');
                Route::post('', 'Company\\Company\\Group\\GroupController@store');
                Route::post('search', 'Company\\Company\\Group\\GroupController@search');

                // group detail
                Route::get('{group}', 'Company\\Company\\Group\\GroupController@show')->name('groups.show');
                Route::get('{group}/edit', 'Company\\Company\\Group\\GroupController@edit')->name('groups.edit');
                Route::post('{group}/update', 'Company\\Company\\Group\\GroupController@update');
                Route::get('{group}/delete', 'Company\\Company\\Group\\GroupController@delete')->name('groups.delete');
                Route::delete('{group}', 'Company\\Company\\Group\\GroupController@destroy');

                // members
                Route::get('{group}/members', 'Company\\Company\\Group\\GroupMembersController@index')->name('groups.members.index');
                Route::post('{group}/members/search', 'Company\\Company\\Group\\GroupMembersController@search');
                Route::post('{group}/members/store', 'Company\\Company\\Group\\GroupMembersController@store');
                Route::post('{group}/members/remove', 'Company\\Company\\Group\\GroupMembersController@remove');

                // meetings
                Route::get('{group}/meetings', 'Company\\Company\\Group\\GroupMeetingsController@index')->name('groups.meetings.index');
                Route::get('{group}/meetings/create', 'Company\\Company\\Group\\GroupMeetingsController@create')->name('groups.meetings.new');
                Route::get('{group}/meetings/{meeting}', 'Company\\Company\\Group\\GroupMeetingsController@show')->name('groups.meetings.show');
                Route::delete('{group}/meetings/{meeting}', 'Company\\Company\\Group\\GroupMeetingsController@destroy');
                Route::post('{group}/meetings/{meeting}/toggle', 'Company\\Company\\Group\\GroupMeetingsController@toggleParticipant');
                Route::post('{group}/meetings/{meeting}/search', 'Company\\Company\\Group\\GroupMeetingsController@search');
                Route::post('{group}/meetings/{meeting}/add', 'Company\\Company\\Group\\GroupMeetingsController@addParticipant');
                Route::post('{group}/meetings/{meeting}/remove', 'Company\\Company\\Group\\GroupMeetingsController@removeParticipant');
                Route::post('{group}/meetings/{meeting}/setDate', 'Company\\Company\\Group\\GroupMeetingsController@setDate');
                Route::post('{group}/meetings/{meeting}/addAgendaItem', 'Company\\Company\\Group\\GroupMeetingsController@createAgendaItem');
                Route::post('{group}/meetings/{meeting}/updateAgendaItem/{agendaItem}', 'Company\\Company\\Group\\GroupMeetingsController@updateAgendaItem');
                Route::delete('{group}/meetings/{meeting}/agendaItem/{agendaItem}', 'Company\\Company\\Group\\GroupMeetingsController@destroyAgendaItem');
                Route::post('{group}/meetings/{meeting}/agendaItem/{agendaItem}/addDecision', 'Company\\Company\\Group\\GroupMeetingsController@createDecision');
                Route::delete('{group}/meetings/{meeting}/agendaItem/{agendaItem}/decisions/{meetingDecision}', 'Company\\Company\\Group\\GroupMeetingsController@destroyDecision');
                Route::get('{group}/meetings/{meeting}/presenters', 'Company\\Company\\Group\\GroupMeetingsController@getPresenters');
            });

            // HR tab
            Route::prefix('hr')->group(function () {
                Route::get('', 'Company\\Company\\HR\\CompanyHRController@index');

                // position
                Route::get('positions/{position}', 'Company\\Company\\HR\\CompanyHRPositionController@show')->name('hr.positions.show');

                // ask me anything
                Route::get('ask-me-anything', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@index')->name('hr.ama.index');
                Route::middleware(['hr'])->group(function () {
                    Route::get('ask-me-anything/create', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@create')->name('hr.ama.create');
                    Route::post('ask-me-anything', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@store')->name('hr.ama.store');
                    Route::get('ask-me-anything/{session}/edit', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@edit')->name('hr.ama.edit');
                    Route::put('ask-me-anything/{session}', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@update')->name('hr.ama.update');
                    Route::put('ask-me-anything/{session}/toggle', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@toggleStatus')->name('hr.ama.toggle');
                    Route::get('ask-me-anything/{session}/delete', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@delete')->name('hr.ama.delete');
                    Route::delete('ask-me-anything/{session}', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@destroy')->name('hr.ama.destroy');
                    Route::put('ask-me-anything/{session}/questions/{question}', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@toggle')->name('hr.ama.question.toggle');
                });
                Route::get('ask-me-anything/{session}', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@show')->name('hr.ama.show');
                Route::post('ask-me-anything/{session}', 'Company\\Dashboard\\Me\\DashboardAskMeAnythingQuestionController@store')->name('dashboard.ama.question.store');
                Route::get('ask-me-anything/{session}/answered', 'Company\\Company\\HR\\CompanyHRAskMeAnythingController@showAnswered')->name('hr.ama.show.answered');
            });

            // Knowledge base
            Route::prefix('kb')->group(function () {
                // Wikis
                Route::get('', 'Company\\Company\\KB\\KnowledgeBaseController@index')->name('wikis.index');
                Route::get('create', 'Company\\Company\\KB\\KnowledgeBaseController@create')->name('wikis.new');
                Route::post('', 'Company\\Company\\KB\\KnowledgeBaseController@store');
                Route::get('{wiki}', 'Company\\Company\\KB\\KnowledgeBaseController@show')->name('wikis.show');
                Route::get('{wiki}/edit', 'Company\\Company\\KB\\KnowledgeBaseController@edit')->name('wikis.edit');
                Route::put('{wiki}', 'Company\\Company\\KB\\KnowledgeBaseController@update');
                Route::delete('{wiki}', 'Company\\Company\\KB\\KnowledgeBaseController@destroy')->name('wikis.destroy');

                // Pages
                Route::get('{wiki}/pages/create', 'Company\\Company\\KB\\KnowledgeBasePageController@create')->name('pages.new');
                Route::post('{wiki}/pages', 'Company\\Company\\KB\\KnowledgeBasePageController@store');
                Route::get('{wiki}/pages/{page}', 'Company\\Company\\KB\\KnowledgeBasePageController@show')->name('pages.show');
                Route::get('{wiki}/pages/{page}/edit', 'Company\\Company\\KB\\KnowledgeBasePageController@edit')->name('pages.edit');
                Route::put('{wiki}/pages/{page}', 'Company\\Company\\KB\\KnowledgeBasePageController@update');
                Route::delete('{wiki}/pages/{page}', 'Company\\Company\\KB\\KnowledgeBasePageController@destroy');
            });
        });

        // recruiting tab
        Route::prefix('recruiting')->group(function () {
            // job openings
            Route::get('job-openings', 'Company\\Recruiting\\RecruitingJobOpeningController@index')->name('recruiting.openings.index');
            Route::get('job-openings/fulfilled', 'Company\\Recruiting\\RecruitingJobOpeningController@fulfilled')->name('recruiting.openings.index.fulfilled');
            Route::get('job-openings/create', 'Company\\Recruiting\\RecruitingJobOpeningController@create')->name('recruiting.openings.create');
            Route::get('job-openings/{jobOpening}', 'Company\\Recruiting\\RecruitingJobOpeningController@show')->name('recruiting.openings.show');
            Route::get('job-openings/{jobOpening}/rejected', 'Company\\Recruiting\\RecruitingJobOpeningController@showRejected')->name('recruiting.openings.show.rejected');
            Route::get('job-openings/{jobOpening}/selected', 'Company\\Recruiting\\RecruitingJobOpeningController@showSelected')->name('recruiting.openings.show.selected');
            Route::get('job-openings/{jobOpening}/edit', 'Company\\Recruiting\\RecruitingJobOpeningController@edit')->name('recruiting.openings.edit');
            Route::put('job-openings/{jobOpening}', 'Company\\Recruiting\\RecruitingJobOpeningController@update');
            Route::delete('job-openings/{jobOpening}', 'Company\\Recruiting\\RecruitingJobOpeningController@destroy');
            Route::post('job-openings', 'Company\\Recruiting\\RecruitingJobOpeningController@store');
            Route::post('job-openings/{jobOpening}/toggle', 'Company\\Recruiting\\RecruitingJobOpeningController@toggle');
            Route::post('job-openings/sponsors', 'Company\\Recruiting\\RecruitingJobOpeningController@sponsors');

            // candidates
            Route::get('job-openings/{jobOpening}/candidates/{candidate}', 'Company\\Recruiting\\RecruitingCandidateController@show')->name('recruiting.candidates.show');
            Route::get('job-openings/{jobOpening}/candidates/{candidate}/cv', 'Company\\Recruiting\\RecruitingCandidateController@showCV')->name('recruiting.candidates.cv');
            Route::get('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}', 'Company\\Recruiting\\RecruitingCandidateController@showStage')->name('recruiting.candidates.stage.show');
            Route::post('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}', 'Company\\Recruiting\\RecruitingCandidateController@store');
            Route::get('job-openings/{jobOpening}/candidates/{candidate}/hire', 'Company\\Recruiting\\RecruitingCandidateController@hire')->name('recruiting.candidates.hire');
            Route::post('job-openings/{jobOpening}/candidates/{candidate}/hire', 'Company\\Recruiting\\RecruitingCandidateController@storeHire');

            // participant in candidate stage
            Route::post('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/searchParticipants', 'Company\\Recruiting\\RecruitingCandidateController@searchParticipants');
            Route::post('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/assignParticipant', 'Company\\Recruiting\\RecruitingCandidateController@assignParticipant');
            Route::delete('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/participants/{participant}', 'Company\\Recruiting\\RecruitingCandidateController@removeParticipant');

            // notes in candidate stage
            Route::post('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/notes', 'Company\\Recruiting\\RecruitingCandidateController@notes');
            Route::put('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/notes/{note}', 'Company\\Recruiting\\RecruitingCandidateController@updateNote');
            Route::delete('job-openings/{jobOpening}/candidates/{candidate}/stages/{stage}/notes/{note}', 'Company\\Recruiting\\RecruitingCandidateController@destroyNote');
        });

        // only available to accountant role
        Route::middleware(['accountant'])->group(function () {
            Route::get('dashboard/expenses', 'Company\\Dashboard\\Accountant\\DashboardExpensesController@index');
            Route::get('dashboard/expenses/{expense}/summary', 'Company\\Dashboard\\Accountant\\DashboardExpensesController@summary')->name('dashboard.expenses.summary');
            Route::get('dashboard/expenses/{expense}', 'Company\\Dashboard\\Accountant\\DashboardExpensesController@show')->name('dashboard.expenses.show');
            Route::post('dashboard/expenses/{expense}/accept', 'Company\\Dashboard\\Accountant\\DashboardExpensesController@accept');
            Route::post('dashboard/expenses/{expense}/reject', 'Company\\Dashboard\\Accountant\\DashboardExpensesController@reject');
        });

        // only available to administrator role
        Route::middleware(['administrator'])->group(function () {
            Route::get('account/audit', 'Company\\Adminland\\AdminAuditController@index');

            Route::get('account/general', 'Company\\Adminland\\AdminGeneralController@index');
            Route::post('account/general/rename', 'Company\\Adminland\\AdminGeneralController@rename');
            Route::post('account/general/currency', 'Company\\Adminland\\AdminGeneralController@currency');
            Route::post('account/general/logo', 'Company\\Adminland\\AdminGeneralController@logo');
            Route::post('account/general/date', 'Company\\Adminland\\AdminGeneralController@date');
            Route::post('account/general/location', 'Company\\Adminland\\AdminGeneralController@location');

            Route::get('account/cancel', 'Company\\Adminland\\AdminCancelAccountController@index');
            Route::delete('account/cancel', 'Company\\Adminland\\AdminCancelAccountController@destroy');

            Route::get('account/billing', 'Company\\Adminland\\AdminBillingController@index');
            Route::get('account/billing/{invoice}', 'Company\\Adminland\\AdminBillingController@show')->name('invoices.show');
        });

        // only available to hr role or administrator
        Route::middleware(['hr'])->group(function () {
            // adminland
            Route::get('account', 'Company\\Adminland\\AdminlandController@index');

            // employee list
            Route::get('account/employees', 'Company\\Adminland\\AdminEmployeeController@index')->name('account.employees.index');
            Route::get('account/employees/all', 'Company\\Adminland\\AdminEmployeeController@all')->name('account.employees.all');
            Route::get('account/employees/active', 'Company\\Adminland\\AdminEmployeeController@active')->name('account.employees.active');
            Route::get('account/employees/locked', 'Company\\Adminland\\AdminEmployeeController@locked')->name('account.employees.locked');
            Route::get('account/employees/noHiringDate', 'Company\\Adminland\\AdminEmployeeController@noHiringDate')->name('account.employees.no_hiring_date');

            //employee CRUD
            Route::get('account/employees/create', 'Company\\Adminland\\AdminEmployeeController@create')->name('account.employees.new');
            Route::get('account/employees/upload', 'Company\\Adminland\\AdminUploadEmployeeController@upload')->name('account.employees.upload');
            Route::get('account/employees/upload/archives', 'Company\\Adminland\\AdminUploadEmployeeController@index')->name('account.employees.upload.archive');
            Route::get('account/employees/upload/archives/{archive}', 'Company\\Adminland\\AdminUploadEmployeeController@show')->name('account.employees.upload.archive.show');
            Route::post('account/employees/upload/archives/{archive}/import', 'Company\\Adminland\\AdminUploadEmployeeController@import')->name('account.employees.upload.archive.import');
            Route::post('account/employees', 'Company\\Adminland\\AdminEmployeeController@store')->name('account.employees.create');
            Route::post('account/employees/storeUpload', 'Company\\Adminland\\AdminUploadEmployeeController@store');
            Route::get('account/employees/{employee}/delete', 'Company\\Adminland\\AdminEmployeeController@delete')->name('account.delete');
            Route::delete('account/employees/{employee}', 'Company\\Adminland\\AdminEmployeeController@destroy');
            Route::get('account/employees/{employee}/lock', 'Company\\Adminland\\AdminEmployeeController@lock')->name('account.lock');
            Route::post('account/employees/{employee}/lock', 'Company\\Adminland\\AdminEmployeeController@lockAccount');
            Route::get('account/employees/{employee}/unlock', 'Company\\Adminland\\AdminEmployeeController@unlock')->name('account.unlock');
            Route::post('account/employees/{employee}/unlock', 'Company\\Adminland\\AdminEmployeeController@unlockAccount');
            Route::get('account/employees/{employee}/permissions', 'Company\\Adminland\\AdminEmployeePermissionController@show')->name('account.employees.permission');
            Route::post('account/employees/{employee}/permissions', 'Company\\Adminland\\AdminEmployeePermissionController@store');
            Route::get('account/employees/{employee}/invite', 'Company\\Adminland\\AdminEmployeeController@invite')->name('account.employees.invite');
            Route::post('account/employees/{employee}/invite', 'Company\\Adminland\\AdminEmployeeController@sendInvite');

            // team management
            Route::resource('account/teams', 'Company\\Adminland\\AdminTeamController', ['as' => 'account_teams']);
            Route::get('account/teams/{team}/logs', 'Company\\Adminland\\AdminTeamController@logs');

            // position management
            Route::resource('account/positions', 'Company\\Adminland\\AdminPositionController');

            // flow management
            Route::resource('account/flows', 'Company\\Adminland\\AdminFlowController');

            // employee statuses
            Route::resource('account/employeestatuses', 'Company\\Adminland\\AdminEmployeeStatusController', ['as' => 'account_employeestatuses']);

            // company news
            Route::resource('account/news', 'Company\\Adminland\\AdminCompanyNewsController', ['as' => 'account_news']);

            // pto policies
            Route::resource('account/ptopolicies', 'Company\\Adminland\\AdminPTOPoliciesController');
            Route::get('account/ptopolicies/{ptopolicy}/getHolidays', 'Company\\Adminland\\AdminPTOPoliciesController@getHolidays');

            // questions
            Route::resource('account/questions', 'Company\\Adminland\\AdminQuestionController');
            Route::put('account/questions/{question}/activate', 'Company\\Adminland\\AdminQuestionController@activate')->name('questions.activate');
            Route::put('account/questions/{question}/deactivate', 'Company\\Adminland\\AdminQuestionController@deactivate')->name('questions.deactivate');

            // hardware
            Route::get('account/hardware/available', 'Company\\Adminland\\AdminHardwareController@available');
            Route::get('account/hardware/lent', 'Company\\Adminland\\AdminHardwareController@lent');
            Route::post('account/hardware/search', 'Company\\Adminland\\AdminHardwareController@search');
            Route::resource('account/hardware', 'Company\\Adminland\\AdminHardwareController');

            // software
            Route::get('account/softwares', 'Company\\Adminland\\AdminSoftwareController@index')->name('software.index');
            Route::get('account/softwares/create', 'Company\\Adminland\\AdminSoftwareController@create')->name('software.create');
            Route::post('account/softwares', 'Company\\Adminland\\AdminSoftwareController@store')->name('software.store');
            Route::get('account/softwares/{software}', 'Company\\Adminland\\AdminSoftwareController@show')->name('software.show');
            Route::get('account/softwares/{software}/edit', 'Company\\Adminland\\AdminSoftwareController@edit')->name('software.edit');
            Route::put('account/softwares/{software}', 'Company\\Adminland\\AdminSoftwareController@update');
            Route::post('account/softwares/{software}/search', 'Company\\Adminland\\AdminSoftwareController@potentialEmployees');
            Route::post('account/softwares/{software}/files', 'Company\\Adminland\\AdminSoftwareController@storeFile');
            Route::get('account/softwares/{software}/numberOfEmployeesWhoDontHaveSoftware', 'Company\\Adminland\\AdminSoftwareController@numberOfEmployeesWhoDontHaveSoftware');
            Route::post('account/softwares/{software}/attach', 'Company\\Adminland\\AdminSoftwareController@attach');
            Route::post('account/softwares/{software}/attachAll', 'Company\\Adminland\\AdminSoftwareController@attachAll');
            Route::delete('account/softwares/{software}/{employee}', 'Company\\Adminland\\AdminSoftwareController@detach');
            Route::delete('account/softwares/{software}/files/{file}', 'Company\\Adminland\\AdminSoftwareController@destroyFile');
            Route::delete('account/softwares/{software}', 'Company\\Adminland\\AdminSoftwareController@destroy');

            // expenses
            Route::resource('account/expenses', 'Company\\Adminland\\AdminExpenseController', ['as' => 'account'])->except(['show']);
            Route::post('account/expenses/search', 'Company\\Adminland\\AdminExpenseController@search');
            Route::post('account/expenses/employee', 'Company\\Adminland\\AdminExpenseController@addEmployee');
            Route::post('account/expenses/removeEmployee', 'Company\\Adminland\\AdminExpenseController@removeEmployee');

            // e-coffee
            Route::get('account/ecoffee', 'Company\\Adminland\\AdminECoffeeController@index');
            Route::post('account/ecoffee', 'Company\\Adminland\\AdminECoffeeController@store');

            // work from home
            Route::get('account/workFromHome', 'Company\\Adminland\\AdminWorkFromHomeController@index');
            Route::put('account/workFromHome', 'Company\\Adminland\\AdminWorkFromHomeController@update');

            // recruiting stage templates
            Route::get('account/recruitment', 'Company\\Adminland\\AdminRecruitmentController@index');
            Route::post('account/recruitment', 'Company\\Adminland\\AdminRecruitmentController@store')->name('recruitment.store');
            Route::get('account/recruitment/{template}', 'Company\\Adminland\\AdminRecruitmentController@show')->name('recruitment.show');
            Route::post('account/recruitment/{template}', 'Company\\Adminland\\AdminRecruitmentController@storeStage');
            Route::put('account/recruitment/{template}/stage/{stage}', 'Company\\Adminland\\AdminRecruitmentController@updateStage');
            Route::delete('account/recruitment/{template}/stage/{stage}', 'Company\\Adminland\\AdminRecruitmentController@destroyStage');

            // project management settings
            Route::get('account/project', 'Company\\Adminland\\AdminProjectManagementController@index')->name('projectmanagement.index');
            Route::post('account/project/issueType', 'Company\\Adminland\\AdminProjectManagementController@store')->name('projectmanagement.store');
            Route::put('account/project/issueType/{type}', 'Company\\Adminland\\AdminProjectManagementController@update')->name('projectmanagement.update');
            Route::delete('account/project/issueType/{type}', 'Company\\Adminland\\AdminProjectManagementController@destroy')->name('projectmanagement.destroy');
        });
    });
});
