<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Auth\\LoginController@showLoginForm')->name('default');

// auth
Route::get('signup', 'Auth\\RegisterController@index')->name('signup');
Route::post('signup', 'Auth\\RegisterController@store')->name('signup.attempt');
Route::get('login', 'Auth\\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\\LoginController@login')->name('login.attempt');

Route::get('invite/employee/{link}', 'Auth\\UserInvitationController@check');
Route::post('invite/employee/{link}/join', 'Auth\\UserInvitationController@join');
Route::post('invite/employee/{link}/accept', 'Auth\\UserInvitationController@accept');

Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'Auth\\LoginController@logout');

    Route::get('home', 'HomeController@index')->name('home');
    Route::post('search/employees', 'HeaderSearchController@employees');
    Route::post('search/teams', 'HeaderSearchController@teams');

    Route::post('help', 'HelpController@toggle');

    Route::resource('company', 'Company\\CompanyController')->only(['create', 'store']);

    // only available if user is in the right account
    Route::middleware(['company'])->prefix('{company}')->group(function () {
        Route::get('welcome', 'WelcomeController@index')->name('welcome');
        Route::post('hide', 'WelcomeController@hide');

        Route::get('notifications', 'User\\Notification\\NotificationController@index');
        Route::post('notifications/read', 'User\\Notification\\MarkNotificationAsReadController@store');

        Route::prefix('dashboard')->group(function () {
            Route::get('', 'Company\\Dashboard\\DashboardController@index')->name('dashboard');

            // me
            Route::get('me', 'Company\\Dashboard\\DashboardMeController@index')->name('dashboard.me');

            Route::post('worklog', 'Company\\Dashboard\\DashboardWorklogController@store');
            Route::post('morale', 'Company\\Dashboard\\DashboardMoraleController@store');
            Route::post('workFromHome', 'Company\\Dashboard\\DashboardWorkFromHomeController@store');
            Route::resource('question', 'Company\\Dashboard\\DashboardQuestionController')->only([
                'store', 'update', 'destroy',
            ]);
            Route::post('expense', 'Company\\Dashboard\\DashboardMeExpenseController@store');

            // company
            Route::get('company', 'Company\\Dashboard\\DashboardCompanyController@index')->name('dashboard.company');

            // hr
            Route::get('hr', 'Company\\Dashboard\\DashboardHRController@index')->name('dashboard.hr');

            // team
            Route::get('team', 'Company\\Dashboard\\DashboardTeamController@index')->name('dashboard.team');
            Route::get('team/{team}', 'Company\\Dashboard\\DashboardTeamController@index');
            Route::get('team/{team}/{date}', 'Company\\Dashboard\\DashboardTeamController@worklogDetails');

            // manager
            Route::get('manager', 'Company\\Dashboard\\DashboardManagerController@index')->name('dashboard.manager');
            Route::get('manager/expenses/{expense}', 'Company\\Dashboard\\DashboardManagerController@showExpense')->name('dashboard.manager.expense.show');
            Route::post('manager/expenses/{expense}/accept', 'Company\\Dashboard\\DashboardManagerController@accept');
            Route::post('manager/expenses/{expense}/reject', 'Company\\Dashboard\\DashboardManagerController@reject');

            // rate your manager
            Route::post('manager/rate/{answer}', 'Company\\Dashboard\\DashboardRateYourManagerController@store');
            Route::post('manager/rate/{answer}/comment', 'Company\\Dashboard\\DashboardRateYourManagerController@storeComment');

            // details of one on ones
            Route::get('oneonones/{entry}', 'Company\\Dashboard\\DashboardMeOneOnOneController@show')->name('dashboard.oneonones.show');
            Route::post('oneonones/{entry}/happened', 'Company\\Dashboard\\DashboardMeOneOnOneController@markHappened');

            Route::post('oneonones/{entry}/talkingPoints', 'Company\\Dashboard\\DashboardMeOneOnOneController@storeTalkingPoint');
            Route::post('oneonones/{entry}/talkingPoints/{talkingPoint}/toggle', 'Company\\Dashboard\\DashboardMeOneOnOneController@toggleTalkingPoint');
            Route::post('oneonones/{entry}/talkingPoints/{talkingPoint}', 'Company\\Dashboard\\DashboardMeOneOnOneController@updateTalkingPoint');
            Route::delete('oneonones/{entry}/talkingPoints/{talkingPoint}', 'Company\\Dashboard\\DashboardMeOneOnOneController@destroyTalkingPoint');

            Route::post('oneonones/{entry}/actionItems', 'Company\\Dashboard\\DashboardMeOneOnOneController@storeActionItem');
            Route::post('oneonones/{entry}/actionItems/{actionItem}/toggle', 'Company\\Dashboard\\DashboardMeOneOnOneController@toggleActionItem');
            Route::post('oneonones/{entry}/actionItems/{actionItem}', 'Company\\Dashboard\\DashboardMeOneOnOneController@updateActionItem');
            Route::delete('oneonones/{entry}/actionItems/{actionItem}', 'Company\\Dashboard\\DashboardMeOneOnOneController@destroyActionItem');

            Route::post('oneonones/{entry}/notes', 'Company\\Dashboard\\DashboardMeOneOnOneController@storeNote');
            Route::post('oneonones/{entry}/notes/{note}', 'Company\\Dashboard\\DashboardMeOneOnOneController@updateNote');
            Route::delete('oneonones/{entry}/notes/{note}', 'Company\\Dashboard\\DashboardMeOneOnOneController@destroyNote');
        });

        Route::prefix('employees')->group(function () {
            Route::get('', 'Company\\Employee\\EmployeeController@index')->name('employees.index');
            Route::get('{employee}', 'Company\\Employee\\EmployeeController@show')->name('employees.show');

            Route::middleware(['employeeOrManagerOrAtLeastHR'])->group(function () {
                Route::get('{employee}/performance', 'Company\\Employee\\EmployeePerformanceController@show')->name('employees.show.performance');
                Route::get('{employee}/performance/surveys', 'Company\\Employee\\EmployeeSurveysController@index')->name('employees.show.performance.survey.index');
                Route::get('{employee}/performance/{survey}', 'Company\\Employee\\EmployeeSurveysController@show')->name('employees.show.performance.survey.show');
            });

            Route::middleware(['employeeOrManagerOrAtLeastHR'])->group(function () {
                Route::resource('{employee}/oneonones', 'Company\\Employee\\EmployeeOneOnOneController', ['as' => 'employees'])->only([
                    'index', 'show',
                ]);
            });

            Route::post('{employee}/assignManager', 'Company\\Employee\\EmployeeController@assignManager');
            Route::post('{employee}/assignDirectReport', 'Company\\Employee\\EmployeeController@assignDirectReport');
            Route::post('{employee}/search/hierarchy', 'Company\\Employee\\EmployeeSearchController@hierarchy');
            Route::post('{employee}/unassignManager', 'Company\\Employee\\EmployeeController@unassignManager');
            Route::post('{employee}/unassignDirectReport', 'Company\\Employee\\EmployeeController@unassignDirectReport');

            Route::get('{employee}/logs', 'Company\\Employee\\EmployeeLogsController@index')->name('employee.show.logs');

            Route::get('{employee}/edit', 'Company\\Employee\\EmployeeEditController@show')->name('employee.show.edit');
            Route::get('{employee}/address/edit', 'Company\\Employee\\EmployeeEditController@address');
            Route::post('{employee}/update', 'Company\\Employee\\EmployeeEditController@update');
            Route::post('{employee}/address/update', 'Company\\Employee\\EmployeeEditController@updateAddress');

            Route::resource('{employee}/position', 'Company\\Employee\\EmployeePositionController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/team', 'Company\\Employee\\EmployeeTeamController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/employeestatuses', 'Company\\Employee\\EmployeeStatusController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/pronoun', 'Company\\Employee\\EmployeePronounController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/description', 'Company\\Employee\\EmployeeDescriptionController')->only([
                'store', 'destroy',
            ]);

            Route::resource('{employee}/skills', 'Company\\Employee\\EmployeeSkillController')->only([
                'store', 'destroy',
            ]);
            Route::post('{employee}/skills/search', 'Company\\Employee\\EmployeeSkillController@search');

            // worklogs
            Route::get('{employee}/worklogs', 'Company\\Employee\\EmployeeWorklogController@index')->name('employees.worklogs');
            Route::get('{employee}/worklogs/{year}', 'Company\\Employee\\EmployeeWorklogController@year');
            Route::get('{employee}/worklogs/{year}/{month}', 'Company\\Employee\\EmployeeWorklogController@month');

            // work from home
            Route::get('{employee}/workfromhome', 'Company\\Employee\\EmployeeWorkFromHomeController@index')->name('employees.workfromhome');
            Route::get('{employee}/workfromhome/{year}', 'Company\\Employee\\EmployeeWorkFromHomeController@year');
            Route::get('{employee}/workfromhome/{year}/{month}', 'Company\\Employee\\EmployeeWorkFromHomeController@month');

            // expenses
            Route::resource('{employee}/expenses', 'Company\\Employee\\EmployeeExpenseController', ['as' => 'employee'])->only([
                'index', 'show',
            ]);
        });

        Route::prefix('teams')->group(function () {
            Route::get('', 'Company\\Team\\TeamController@index');
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

        Route::prefix('projects')->group(function () {
            Route::get('', 'Company\\Project\\ProjectController@index');
            Route::get('create', 'Company\\Project\\ProjectController@create');
            Route::post('', 'Company\\Project\\ProjectController@store');
            Route::post('search', 'Company\\Project\\ProjectController@search');

            // project detail
            Route::get('{project}', 'Company\\Project\\ProjectController@show')->name('projects.show');
            Route::get('{project}/summary', 'Company\\Project\\ProjectController@show');

            Route::post('{project}/start', 'Company\\Project\\ProjectController@start');
            Route::post('{project}/pause', 'Company\\Project\\ProjectController@pause');
            Route::post('{project}/close', 'Company\\Project\\ProjectController@close');
            Route::post('{project}/lead/assign', 'Company\\Project\\ProjectController@assign');
            Route::post('{project}/lead/clear', 'Company\\Project\\ProjectController@clear');
            Route::get('{project}/edit', 'Company\\Project\\ProjectController@edit')->name('projects.edit');
            Route::post('{project}/description', 'Company\\Project\\ProjectController@description');
            Route::post('{project}/update', 'Company\\Project\\ProjectController@update');
            Route::get('{project}/delete', 'Company\\Project\\ProjectController@delete')->name('projects.delete');
            Route::delete('{project}', 'Company\\Project\\ProjectController@destroy');

            Route::post('{project}/links', 'Company\\Project\\ProjectController@createLink');
            Route::delete('{project}/links/{link}', 'Company\\Project\\ProjectController@destroyLink');

            Route::get('{project}/messages', 'Company\\Project\\ProjectController@messages');
            Route::get('{project}/messages/{message}', 'Company\\Project\\ProjectController@message');

            Route::get('{project}/status', 'Company\\Project\\ProjectController@createStatus');
            Route::put('{project}/status', 'Company\\Project\\ProjectController@postStatus');
        });

        Route::prefix('company')->group(function () {
            Route::get('', 'Company\\Company\\CompanyController@index');
            Route::post('guessEmployee/vote', 'Company\\Company\\CompanyController@vote');
            Route::get('guessEmployee/replay', 'Company\\Company\\CompanyController@replay');

            // Questions and answers
            Route::resource('questions', 'Company\\Company\\QuestionController', ['as' => 'company'])->only([
                'index', 'show',
            ]);
            Route::get('questions/{question}/teams/{team}', 'Company\\Company\\QuestionController@team');

            // Skills
            Route::get('skills', 'Company\\Company\\SkillController@index')->name('company.skills.index');
            Route::get('skills/{skill}', 'Company\\Company\\SkillController@show')->name('company.skills.show');
            Route::put('skills/{skill}', 'Company\\Company\\SkillController@update');
            Route::delete('skills/{skill}', 'Company\\Company\\SkillController@destroy');
        });

        // only available to accountant role
        Route::middleware(['accountant'])->group(function () {
            Route::get('dashboard/expenses', 'Company\\Dashboard\\DashboardExpensesController@index');
            Route::get('dashboard/expenses/{expense}/summary', 'Company\\Dashboard\\DashboardExpensesController@summary')->name('dashboard.expenses.summary');
            Route::get('dashboard/expenses/{expense}', 'Company\\Dashboard\\DashboardExpensesController@show')->name('dashboard.expenses.show');
            Route::post('dashboard/expenses/{expense}/accept', 'Company\\Dashboard\\DashboardExpensesController@accept');
            Route::post('dashboard/expenses/{expense}/reject', 'Company\\Dashboard\\DashboardExpensesController@reject');
        });

        // only available to administrator role
        Route::middleware(['administrator'])->group(function () {
            Route::get('account/audit', 'Company\\Adminland\\AdminAuditController@index');

            Route::get('account/general', 'Company\\Adminland\\AdminGeneralController@index');
            Route::post('account/general/rename', 'Company\\Adminland\\AdminGeneralController@rename');
            Route::post('account/general/currency', 'Company\\Adminland\\AdminGeneralController@currency');
        });

        // only available to hr role
        Route::middleware(['hr'])->group(function () {
            // adminland
            Route::get('account', 'Company\\Adminland\\AdminlandController@index');

            // employee list
            Route::get('account/employees', 'Company\\Adminland\\AdminEmployeeController@index')->name('account.employees.index');
            Route::get('account/employees/all', 'Company\\Adminland\\AdminEmployeeController@all')->name('account.employees.all');
            Route::get('account/employees/active', 'Company\\Adminland\\AdminEmployeeController@active')->name('account.employees.active');
            Route::get('account/employees/locked', 'Company\\Adminland\\AdminEmployeeController@locked')->name('account.employees.locked');
            Route::get('account/employees/noHiringDate', 'Company\\Adminland\\AdminEmployeeController@noHiringDate')->name('account.employees.no_hiring_date');
            Route::get('account/employees/permission', 'Company\\Adminland\\AdminEmployeeController@permission')->name('account.employees.permission');

            //employee CRUD
            Route::get('account/employees/create', 'Company\\Adminland\\AdminEmployeeController@create')->name('account.employees.new');
            Route::post('account/employees', 'Company\\Adminland\\AdminEmployeeController@store')->name('account.employees.create');
            Route::get('account/employees/{employee}/delete', 'Company\\Adminland\\AdminEmployeeController@delete')->name('account.delete');
            Route::delete('account/employees/{employee}', 'Company\\Adminland\\AdminEmployeeController@destroy');
            Route::get('account/employees/{employee}/lock', 'Company\\Adminland\\AdminEmployeeController@lock')->name('account.lock');
            Route::post('account/employees/{employee}/lock', 'Company\\Adminland\\AdminEmployeeController@lockAccount');
            Route::get('account/employees/{employee}/unlock', 'Company\\Adminland\\AdminEmployeeController@unlock')->name('account.unlock');
            Route::post('account/employees/{employee}/unlock', 'Company\\Adminland\\AdminEmployeeController@unlockAccount');
            Route::get('account/employees/{employee}/permissions', 'Company\\Adminland\\PermissionController@index');
            Route::post('account/employees/{employee}/permissions', 'Company\\Adminland\\PermissionController@store');

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
            Route::post('account/questions/{question}/activate', 'Company\\Adminland\\AdminQuestionController@activate');
            Route::post('account/questions/{question}/deactivate', 'Company\\Adminland\\AdminQuestionController@deactivate');

            // hardware
            Route::get('account/hardware/available', 'Company\\Adminland\\AdminHardwareController@available');
            Route::get('account/hardware/lent', 'Company\\Adminland\\AdminHardwareController@lent');
            Route::post('account/hardware/search', 'Company\\Adminland\\AdminHardwareController@search');
            Route::resource('account/hardware', 'Company\\Adminland\\AdminHardwareController');

            // expenses
            Route::resource('account/expenses', 'Company\\Adminland\\AdminExpenseController', ['as' => 'account'])->except(['show']);
            Route::post('account/expenses/search', 'Company\\Adminland\\AdminExpenseController@search');
            Route::post('account/expenses/employee', 'Company\\Adminland\\AdminExpenseController@addEmployee');
            Route::post('account/expenses/removeEmployee', 'Company\\Adminland\\AdminExpenseController@removeEmployee');
        });
    });
});
