<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'Auth\\LoginController@showLoginForm')->name('default');

// @see vendor/laravel/ui/src/AuthRouteMethods.php
Auth::routes([
    'login' => false,
    'register' => false,
    'verify' => true,
]);

// auth
Route::get('signup', 'Auth\\RegisterController@index')->name('signup');
Route::post('signup', 'Auth\\RegisterController@store')->name('signup.attempt');
Route::get('login', 'Auth\\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\\LoginController@login')->name('login.attempt');

Route::get('invite/employee/{link}', 'Auth\\UserInvitationController@check');
Route::post('invite/employee/{link}/join', 'Auth\\UserInvitationController@join')->name('invitation.join');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::post('search/employees', 'HeaderSearchController@employees');
    Route::post('search/teams', 'HeaderSearchController@teams');

    Route::put('help', 'HelpController@toggle');

    Route::resource('company', 'Company\\CompanyController')->only([
        'create', 'store',
    ]);

    // only available if user is in the right account
    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::get('welcome', 'WelcomeController@index')->name('welcome');
        Route::put('hide', 'WelcomeController@hide');

        Route::get('notifications', 'User\\Notification\\NotificationController@index');
        Route::put('notifications/read', 'User\\Notification\\MarkNotificationAsReadController@store');

        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('', 'Company\\Dashboard\\DashboardController@index')->name('index');

            // me
            Route::get('me', 'Company\\Dashboard\\DashboardMeController@index')->name('me');

            Route::resources([
                'worklog' => 'Company\\Dashboard\\DashboardWorklogController',
                'morale' => 'Company\\Dashboard\\DashboardMoraleController',
                'workFromHome' => 'Company\\Dashboard\\DashboardWorkFromHomeController',
                'expense' => 'Company\\Dashboard\\DashboardMeExpenseController',
            ], [
                'only' => [
                    'store',
                ],
            ]);
            Route::resource('question', 'Company\\Dashboard\\DashboardQuestionController')->only([
                'store', 'update', 'destroy',
            ]);

            // company
            Route::resource('company', 'Company\\Dashboard\\DashboardCompanyController')->only([
                'index',
            ]);

            // hr
            Route::resource('hr', 'Company\\Dashboard\\DashboardHRController')->only([
                'index',
            ]);

            // team
            Route::resource('team', 'Company\\Dashboard\\DashboardTeamController')->only([
                'index', 'show',
            ]);
            Route::get('team/{team}/{date}', 'Company\\Dashboard\\DashboardTeamController@worklogDetails');

            // manager
            Route::resource('manager', 'Company\\Dashboard\\DashboardManagerController')->only([
                'index',
            ]);
            Route::get('manager/expenses/{expense}', 'Company\\Dashboard\\DashboardManagerController@showExpense')->name('manager.expense.show');
            Route::put('manager/expenses/{expense}/accept', 'Company\\Dashboard\\DashboardManagerController@accept');
            Route::put('manager/expenses/{expense}/reject', 'Company\\Dashboard\\DashboardManagerController@reject');

            // rate your manager
            Route::post('manager/rate/{answer}', 'Company\\Dashboard\\DashboardRateYourManagerController@store');
            Route::post('manager/rate/{answer}/comment', 'Company\\Dashboard\\DashboardRateYourManagerController@storeComment');

            // details of one on ones
            Route::prefix('oneonones/{entry}')->name('oneonones.')->group(function () {
                Route::get('', 'Company\\Dashboard\\DashboardMeOneOnOneController@show')->name('show');
                Route::put('happened', 'Company\\Dashboard\\DashboardMeOneOnOneController@markHappened');

                Route::resources([
                    'talkingPoints' => 'Company\\Dashboard\\OneOnOne\\DashboardMeOneOnOneTalkingPointController',
                    'actionItems' => 'Company\\Dashboard\\OneOnOne\\DashboardMeOneOnOneActionItemController',
                    'notes' => 'Company\\Dashboard\\OneOnOne\\DashboardMeOneOnOneNoteController',
                ], [
                    'only' => [
                        'store', 'update', 'destroy',
                    ],
                ]);
                Route::put('talkingPoints/{talkingPoint}/toggle', 'Company\\Dashboard\\OneOnOne\\DashboardMeOneOnOneTalkingPointController@toggle');
                Route::put('actionItems/{actionItem}/toggle', 'Company\\Dashboard\\OneOnOne\\DashboardMeOneOnOneActionItemController@toggle');
            });
        });

        Route::resource('employees', 'Company\\Employee\\EmployeeController')->only([
            'index', 'show', 'edit', 'update',
        ]);
        Route::prefix('employees/{employee}')->name('employees.')->group(function () {
            Route::middleware(['employeeOrManagerOrAtLeastHR'])->group(function () {
                Route::get('performance', 'Company\\Employee\\EmployeePerformanceController@show');
                Route::resource('performance/surveys', 'Company\\Employee\\EmployeeSurveysController', ['as' => 'performance'])->only([
                    'index', 'show',
                ]);
            });

            Route::middleware(['employeeOrManagerOrAtLeastHR'])->group(function () {
                Route::resource('oneonones', 'Company\\Employee\\EmployeeOneOnOneController')->only([
                    'index', 'show',
                ]);
            });

            Route::put('assignManager', 'Company\\Employee\\EmployeeController@assignManager')->name('manager.assign');
            Route::put('assignDirectReport', 'Company\\Employee\\EmployeeController@assignDirectReport')->name('directReport.assign');
            Route::post('search/hierarchy', 'Company\\Employee\\EmployeeSearchController@hierarchy');
            Route::put('unassignManager', 'Company\\Employee\\EmployeeController@unassignManager')->name('manager.unassign');
            Route::put('unassignDirectReport', 'Company\\Employee\\EmployeeController@unassignDirectReport')->name('directReport.unassign');

            Route::get('logs', 'Company\\Employee\\EmployeeLogsController@index')->name('logs.index');

            Route::get('address/edit', 'Company\\Employee\\EmployeeAddressController@edit')->name('address.edit');
            Route::put('address', 'Company\\Employee\\EmployeeAddressController@update')->name('address.update');

            Route::resources([
                'position' => 'Company\\Employee\\EmployeePositionController',
                'team' => 'Company\\Employee\\EmployeeTeamController',
                'employeestatuses' => 'Company\\Employee\\EmployeeStatusController',
                'pronoun' => 'Company\\Employee\\EmployeePronounController',
                'description' => 'Company\\Employee\\EmployeeDescriptionController',
                'skills' => 'Company\\Employee\\EmployeeSkillController',
            ], [
                'only' => [
                    'store', 'destroy',
                ],
            ]);

            Route::post('skills/search', 'Company\\Employee\\EmployeeSkillController@search')->name('skills.search');

            // worklogs
            Route::get('worklogs', 'Company\\Employee\\EmployeeWorklogController@index')->name('worklogs');
            Route::get('worklogs/{year}', 'Company\\Employee\\EmployeeWorklogController@year');
            Route::get('worklogs/{year}/{month}', 'Company\\Employee\\EmployeeWorklogController@month');

            // work from home
            Route::get('workfromhome', 'Company\\Employee\\EmployeeWorkFromHomeController@index')->name('workfromhome');
            Route::get('workfromhome/{year}', 'Company\\Employee\\EmployeeWorkFromHomeController@year');
            Route::get('workfromhome/{year}/{month}', 'Company\\Employee\\EmployeeWorkFromHomeController@month');

            // expenses
            Route::resource('expenses', 'Company\\Employee\\EmployeeExpenseController')->only([
                'index', 'show',
            ]);
        });

        Route::resource('teams', 'Company\\Team\\TeamController')->only([
            'index', 'show',
        ]);
        Route::prefix('teams/{team}')->name('teams.')->group(function () {
            Route::post('members/search', 'Company\\Team\\TeamMembersController@index');
            Route::put('members/{employee}/attach', 'Company\\Team\\TeamMembersController@attach');
            Route::put('members/{employee}/detach', 'Company\\Team\\TeamMembersController@detach');

            Route::resources([
                'description' => 'Company\\Team\\TeamDescriptionController',
                'lead' => 'Company\\Team\\TeamLeadController',
                'links' => 'Company\\Team\\TeamUsefulLinkController',
            ], [
                'only' =>[
                    'store', 'destroy',
                ],
            ]);

            Route::post('lead/search', 'Company\\Team\\TeamLeadController@search');

            Route::resource('news', 'Company\\Team\\TeamNewsController');

            Route::resource('ships', 'Company\\Team\\TeamRecentShipController');
            Route::post('ships/search', 'Company\\Team\\TeamRecentShipController@search');
        });

        Route::resource('projects', 'Company\\Project\\ProjectController');
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::post('search', 'Company\\Project\\ProjectController@search')->name('search');
            Route::get('{project}/delete', 'Company\\Project\\ProjectController@delete')->name('delete');

            // project detail
            Route::post('{project}/start', 'Company\\Project\\ProjectController@start');
            Route::post('{project}/pause', 'Company\\Project\\ProjectController@pause');
            Route::post('{project}/close', 'Company\\Project\\ProjectController@close');
            Route::post('{project}/lead/assign', 'Company\\Project\\ProjectController@assign');
            Route::post('{project}/lead/clear', 'Company\\Project\\ProjectController@clear');
            Route::post('{project}/description', 'Company\\Project\\ProjectController@description');

            Route::resource('{project}/links', 'Company\\Project\\ProjectLinkController')->only([
                'store', 'destroy'
            ]);
            Route::resource('{project}/status', 'Company\\Project\\ProjectLinkController@create')->only([
                'create', 'store'
            ]);

            // project decision logs
            Route::post('{project}/decisions/search', 'Company\\Project\\ProjectDecisionsController@search');
            Route::resource('{project}/decisions', 'Company\\Project\\ProjectDecisionsController')->only([
                'index', 'store', 'destroy',
            ]);

            // project members
            Route::get('{project}/members/search', 'Company\\Project\\ProjectMembersController@search');
            Route::resource('{project}/members', 'Company\\Project\\ProjectMembersController')->only([
                'index', 'store', 'destroy',
            ]);

            // project messages
            Route::resource('{project}/messages', 'Company\\Project\\ProjectMessagesController', ['as' => 'projects']);
        });

        Route::prefix('company')->name('company.')->group(function () {
            Route::get('', 'Company\\Company\\CompanyController@index')->name('index');
            Route::put('guessEmployee/vote', 'Company\\Company\\CompanyController@vote');
            Route::get('guessEmployee/replay', 'Company\\Company\\CompanyController@replay');

            // Questions and answers
            Route::resource('questions', 'Company\\Company\\QuestionController')->only([
                'index', 'show',
            ]);
            Route::get('questions/{question}/teams/{team}', 'Company\\Company\\QuestionController@team');

            // Skills
            Route::resource('skills', 'Company\\Company\\SkillController')->only([
                'index', 'show', 'update', 'destroy',
            ]);
        });

        // only available to accountant role
        Route::middleware(['accountant'])->prefix('dashboard/expenses')->name('dashboard.expenses.')->group(function () {
            Route::get('', 'Company\\Dashboard\\DashboardExpensesController@index')->name('index');
            Route::get('{expense}', 'Company\\Dashboard\\DashboardExpensesController@show')->name('show');
            Route::get('{expense}/summary', 'Company\\Dashboard\\DashboardExpensesController@summary')->name('summary');
            Route::put('{expense}/accept', 'Company\\Dashboard\\DashboardExpensesController@accept')->name('accept');
            Route::put('{expense}/reject', 'Company\\Dashboard\\DashboardExpensesController@reject')->name('reject');
        });

        // only available to administrator role
        Route::middleware(['administrator'])->prefix('account')->group(function () {
            Route::get('audit', 'Company\\Adminland\\AdminAuditController@index');

            Route::get('general', 'Company\\Adminland\\AdminGeneralController@index');
            Route::put('general/rename', 'Company\\Adminland\\AdminGeneralController@rename');
            Route::put('general/currency', 'Company\\Adminland\\AdminGeneralController@currency');
        });

        // only available to hr role
        Route::middleware(['hr'])->prefix('account')->name('account.')->group(function () {
            // adminland
            Route::get('', 'Company\\Adminland\\AdminlandController@index')->name('index');

            Route::resource('employees', 'Company\\Adminland\\AdminEmployeeController')->only([
                'create', 'store', 'destroy',
            ]);
            Route::prefix('employees')->name('employees.')->group(function () {
                // employee list
                Route::get('', 'Company\\Adminland\\AdminEmployeeController@index')->name('index');
                Route::get('all', 'Company\\Adminland\\AdminEmployeeController@all')->name('all');
                Route::get('active', 'Company\\Adminland\\AdminEmployeeController@active')->name('active');
                Route::get('locked', 'Company\\Adminland\\AdminEmployeeController@locked')->name('locked');
                Route::get('noHiringDate', 'Company\\Adminland\\AdminEmployeeController@noHiringDate')->name('no_hiring_date');
                Route::get('permission', 'Company\\Adminland\\AdminEmployeeController@permission')->name('permission');

                Route::get('{employee}/delete', 'Company\\Adminland\\AdminEmployeeController@delete')->name('delete');
                Route::get('{employee}/lock', 'Company\\Adminland\\AdminEmployeeController@lock')->name('lock');
                Route::put('{employee}/lock', 'Company\\Adminland\\AdminEmployeeController@lockAccount')->name('lock.update');
                Route::get('{employee}/unlock', 'Company\\Adminland\\AdminEmployeeController@unlock')->name('unlock');
                Route::put('{employee}/unlock', 'Company\\Adminland\\AdminEmployeeController@unlockAccount')->name('unlock.update');
                Route::get('{employee}/permissions', 'Company\\Adminland\\PermissionController@index');
                Route::post('{employee}/permissions', 'Company\\Adminland\\PermissionController@store');
            });

            // team management
            Route::resource('teams', 'Company\\Adminland\\AdminTeamController');
            Route::get('teams/{team}/logs', 'Company\\Adminland\\AdminTeamController@logs');

            // position management
            Route::resource('positions', 'Company\\Adminland\\AdminPositionController');

            // flow management
            Route::resource('flows', 'Company\\Adminland\\AdminFlowController');

            // employee statuses
            Route::resource('employeestatuses', 'Company\\Adminland\\AdminEmployeeStatusController');

            // company news
            Route::resource('news', 'Company\\Adminland\\AdminCompanyNewsController');

            // pto policies
            Route::resource('ptopolicies', 'Company\\Adminland\\AdminPTOPoliciesController');
            Route::get('ptopolicies/{ptopolicy}/getHolidays', 'Company\\Adminland\\AdminPTOPoliciesController@getHolidays');

            // questions
            Route::resource('questions', 'Company\\Adminland\\AdminQuestionController');
            Route::put('questions/{question}/activate', 'Company\\Adminland\\AdminQuestionController@activate')->name('questions.activate');
            Route::put('questions/{question}/deactivate', 'Company\\Adminland\\AdminQuestionController@deactivate')->name('questions.deactivate');

            // hardware
            Route::get('hardware/available', 'Company\\Adminland\\AdminHardwareController@available');
            Route::get('hardware/lent', 'Company\\Adminland\\AdminHardwareController@lent');
            Route::post('hardware/search', 'Company\\Adminland\\AdminHardwareController@search');
            Route::resource('hardware', 'Company\\Adminland\\AdminHardwareController');

            // expenses
            Route::resource('expenses', 'Company\\Adminland\\AdminExpenseController')->except(['show']);
            Route::post('expenses/search', 'Company\\Adminland\\AdminExpenseController@search');
            Route::post('expenses/employee', 'Company\\Adminland\\AdminExpenseController@addEmployee');
            Route::post('expenses/removeEmployee', 'Company\\Adminland\\AdminExpenseController@removeEmployee');
        });
    });
});
