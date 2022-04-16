<?php

namespace App\Console\Commands\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Faker\Factory as Faker;
use App\Jobs\LogTeamsMorale;
use App\Models\Company\File;
use App\Models\Company\Team;
use App\Models\User\Pronoun;
use App\Models\Company\Company;
use App\Models\Company\Project;
use Illuminate\Console\Command;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\Question;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ECoffeeMatch;
use App\Services\User\CreateAccount;
use App\Models\Company\ProjectStatus;
use App\Models\Company\CompanyInvoice;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\ExpenseCategory;
use App\Services\Company\Wiki\CreateWiki;
use App\Services\Company\Team\SetTeamLead;
use App\Services\Company\Group\CreateGroup;
use App\Models\Company\AskMeAnythingSession;
use App\Services\Company\Wiki\AddPageToWiki;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use App\Services\Company\Group\CreateMeeting;
use App\Services\Company\Project\StartProject;
use App\Services\Company\Team\Ship\CreateShip;
use App\Models\Company\EmployeePositionHistory;
use App\Models\Company\RecruitingStageTemplate;
use App\Services\Company\Project\CreateProject;
use App\Models\Company\CompanyDailyUsageHistory;
use App\Services\Company\Group\CreateAgendaItem;
use App\Services\Company\Group\UpdateMeetingDate;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Models\Company\CompanyUsageHistoryDetails;
use App\Services\Company\Adminland\Team\CreateTeam;
use App\Services\Company\Employee\Morale\LogMorale;
use App\Services\Company\Project\CreateProjectLink;
use App\Services\Company\Project\CreateProjectTask;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Services\Company\Group\CreateMeetingDecision;
use App\Services\Company\Project\CreateProjectStatus;
use App\Services\Company\Employee\Answer\CreateAnswer;
use App\Services\Company\Project\AddEmployeeToProject;
use App\Services\Company\Project\CreateProjectMessage;
use App\Services\Company\Project\CreateProjectDecision;
use App\Services\Company\Project\CreateProjectTaskList;
use App\Services\Company\Employee\Expense\CreateExpense;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Services\Company\Adminland\Company\CreateCompany;
use App\Services\Company\Adminland\Hardware\LendHardware;
use App\Services\Company\Employee\Birthdate\SetBirthdate;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Project\MarkProjectMessageasRead;
use App\Services\Company\Adminland\Hardware\CreateHardware;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\Question\CreateQuestion;
use App\Services\Company\Adminland\Software\CreateSoftware;
use App\Services\Company\Employee\HiringDate\SetHiringDate;
use App\Services\Company\Employee\Timesheet\RejectTimesheet;
use App\Services\Company\Employee\Timesheet\SubmitTimesheet;
use App\Services\Company\Employee\Timesheet\ApproveTimesheet;
use App\Services\Company\Project\AssignProjectTaskToEmployee;
use App\Services\Company\Team\Description\SetTeamDescription;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneNote;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;
use App\Services\Company\Adminland\JobOpening\CreateJobOpening;
use App\Services\Company\Adminland\JobOpening\ToggleJobOpening;
use App\Services\Company\Adminland\Software\GiveSeatToEmployee;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneEntry;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;
use App\Services\Company\Employee\Contract\SetContractRenewalDate;
use App\Services\Company\Employee\Pronoun\AssignPronounToEmployee;
use App\Services\Company\Employee\ECoffee\MatchEmployeesForECoffee;
use App\Services\Company\Adminland\JobOpening\CreateRecruitingStage;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneActionItem;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneActionItem;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;
use App\Services\Company\Employee\Timesheet\CreateTimeTrackingEntry;
use App\Services\Company\Employee\Description\SetPersonalDescription;
use App\Services\Company\Employee\OneOnOne\CreateOneOnOneTalkingPoint;
use App\Services\Company\Employee\OneOnOne\ToggleOneOnOneTalkingPoint;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;
use App\Services\Company\Employee\OneOnOne\MarkOneOnOneEntryAsHappened;
use App\Services\Company\Adminland\Expense\AllowEmployeeToManageExpenses;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingSession;
use App\Services\Company\Adminland\AskMeAnything\ToggleAskMeAnythingSession;
use App\Services\Company\Adminland\JobOpening\CreateRecruitingStageTemplate;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingQuestion;
use App\Services\Company\Employee\WorkFromHome\UpdateWorkFromHomeInformation;
use App\Services\Company\Employee\EmployeeStatus\AssignEmployeeStatusToEmployee;

class SetupDummyAccount extends Command
{
    protected ProgressBar $progress;
    protected Company $company;
    protected Collection $employees;
    protected Collection $teams;

    // All the employees
    protected Employee $jan;
    protected Employee $michael;
    protected Employee $dwight;
    protected Employee $phyllis;
    protected Employee $jim;
    protected Employee $kelly;
    protected Employee $angela;
    protected Employee $oscar;
    protected Employee $dakota;
    protected Employee $toby;
    protected Employee $kevin;
    protected Employee $erin;
    protected Employee $pete;
    protected Employee $meredith;
    protected Employee $val;
    protected Employee $nate;
    protected Employee $glenn;
    protected Employee $philip;
    protected Employee $debra;

    // The employee statuses
    protected EmployeeStatus $employeeStatusPartTime;
    protected EmployeeStatus $employeeStatusFullTime;
    protected EmployeeStatus $employeeStatusConsultant;

    // Positions
    protected Position $positionRegionalManager;
    protected Position $positionAssistantToTheRegionalManager;
    protected Position $positionRegionalDirectorOfSales;
    protected Position $positionSalesRep;
    protected Position $positionTravelingSalesRepresentative;
    protected Position $positionSeniorAccountant;
    protected Position $positionHeadOfAccounting;
    protected Position $positionAccountant;
    protected Position $positionHRRep;
    protected Position $positionReceptionist;
    protected Position $positionCustomerServiceRepresentative;
    protected Position $positionSupplierRelationsRep;
    protected Position $positionQualityAssurance;
    protected Position $positionWarehouseForeman;
    protected Position $positionWarehouseStaff;

    // Teams
    protected Team $teamManagement;
    protected Team $teamSales;
    protected Team $teamAccounting;
    protected Team $teamHumanResources;
    protected Team $teamReception;
    protected Team $teamProductOversight;
    protected Team $teamWarehouse;

    // Pronouns
    protected Pronoun $pronounHeHim;
    protected Pronoun $pronounSheHer;
    protected Pronoun $pronounTheyThem;

    // Expense categories
    protected ExpenseCategory $expenseCategoryMaintenanceAndRepairs;
    protected ExpenseCategory $expenseCategoryMealsAndEntertainment;
    protected ExpenseCategory $expenseCategoryOfficeExpense;
    protected ExpenseCategory $expenseCategoryTravel;
    protected ExpenseCategory $expenseCategoryMotorVehicleExpenses;

    // Questions
    protected Question $questionWhatIsYourFavoriteAnimal;
    protected Question $questionWhatIsTheBestMovieYouHaveSeenThisYear;
    protected Question $questionCareToShareYourBestRestaurantInTown;
    protected Question $questionWhatIsYourFavoriteBand;
    protected Question $questionWhatAreTheCurrentHighlightsOfThisYearForYou;
    protected Question $questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek;
    protected Question $questionWhatIsTheBestTVShowOfThisYearSoFar;

    // Projects
    protected Project $projectInfinity;

    protected ?\Faker\Generator $faker;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:dummyaccount
                            {--skip-refresh : Don\'t refresh the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare an account with fake data so users can play with it';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->start();
        if (! $this->option('skip-refresh')) {
            $this->wipeAndMigrateDB();
        }
        $this->createFirstUser();
        $this->assignAccountantRole();
        $this->createEmployeeStatuses();
        $this->createPositions();
        $this->createTeams();
        $this->addTeamDescriptions();
        $this->createEmployees();
        $this->addAvatars();
        $this->createFutureEmployees();
        $this->addSkills();
        $this->addWorkFromHomeEntries();
        $this->addWorklogEntriesAndMorale();
        $this->createQuestions();
        $this->addAnswers();
        $this->addExpenses();
        $this->createHardware();
        $this->createSoftware();
        $this->addRecentShips();
        $this->addRateYourManagerSurveys();
        $this->addOneOnOnes();
        $this->addProjects();
        $this->createTimeTrackingEntries();
        $this->setContractRenewalDates();
        $this->setECoffeeProcess();
        $this->addGroups();
        $this->addPreviousPositionsHistory();
        $this->addBillingAndInvoices();
        $this->addWikis();
        $this->addRecruitingStages();
        $this->addJobOpenings();
        $this->addAMASessions();
        $this->addSecondaryBlankAccount();
        $this->validateUserAccounts();
        $this->stop();
    }

    private function start(): void
    {
        if (! $this->confirm('Are you sure you want to proceed? This will delete ALL data in your environment.')) {
            exit;
        }

        $this->line('This process will take a few minutes to complete. Be patient and read a book in the meantime.');

        $this->faker = Faker::create();
    }

    private function stop(): void
    {
        $this->line('');
        $this->line('-----------------------------');
        $this->line('|');
        $this->line('| Welcome to OfficeLife');
        $this->line('|');
        $this->line('-----------------------------');
        $this->info('| You can now sign in with one of these two accounts:');
        $this->line('| An account with a lot of data:');
        $this->line('| username: admin@admin.com');
        $this->line('| password: admin123');
        $this->line('|------------------------–––-');
        $this->line('|A blank account:');
        $this->line('| username: blank@blank.com');
        $this->line('| password: blank123');
        $this->line('|------------------------–––-');
        $this->line('| URL:      '.config('app.url'));
        $this->line('-----------------------------');

        $this->info('Setup is done. Have fun.');
    }

    private function wipeAndMigrateDB(): void
    {
        $this->artisan('☐ Migration of the database', 'migrate:fresh');
        $this->artisan('☐ Symlink the storage folder', 'storage:link');
    }

    private function createFirstUser(): void
    {
        $this->info('☐ Create first user of the account');

        $user = (new CreateAccount)->execute([
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'first_name' => 'Michael',
            'last_name' => 'Scott',
        ]);

        $this->company = (new CreateCompany)->execute([
            'author_id' => $user->id,
            'name' => 'Dunder Mifflin',
        ]);

        // grab the employee that was just created
        $this->michael = Employee::first();

        $this->pronounHeHim = Pronoun::where('label', trans('account.pronoun_he_him'))->first();
        $this->pronounSheHer = Pronoun::where('label', trans('account.pronoun_she_her'))->first();
        $this->pronounTheyThem = Pronoun::where('label', trans('account.pronoun_they_them'))->first();

        $this->expenseCategoryMaintenanceAndRepairs = ExpenseCategory::where('name', trans('account.expense_category_default_maintenance_and_repairs'))->first();
        $this->expenseCategoryMealsAndEntertainment = ExpenseCategory::where('name', trans('account.expense_category_default_meals_and_entertainment'))->first();
        $this->expenseCategoryOfficeExpense = ExpenseCategory::where('name', trans('account.expense_category_default_office_expense'))->first();
        $this->expenseCategoryTravel = ExpenseCategory::where('name', trans('account.expense_category_default_travel'))->first();
        $this->expenseCategoryMotorVehicleExpenses = ExpenseCategory::where('name', trans('account.expense_category_default_motor_vehicle_expenses'))->first();
    }

    private function assignAccountantRole(): void
    {
        $this->info('☐ Assign accountant role');

        (new AllowEmployeeToManageExpenses)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $this->michael->id,
        ]);
    }

    private function createEmployeeStatuses(): void
    {
        $this->info('☐ Create employee statuses');

        $this->employeeStatusFullTime = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Full time',
            'type' => EmployeeStatus::INTERNAL,
        ]);

        $this->employeeStatusPartTime = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Part time',
            'type' => EmployeeStatus::INTERNAL,
        ]);

        $this->employeeStatusConsultant = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Consultant',
            'type' => EmployeeStatus::EXTERNAL,
        ]);
    }

    private function createPositions(): void
    {
        $this->info('☐ Create employee positions');

        $this->positionRegionalManager = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Regional Manager',
        ]);

        $this->positionAssistantToTheRegionalManager = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Assistant to the Regional Manager',
        ]);

        $this->positionRegionalDirectorOfSales = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Regional Director of Sales',
        ]);

        $this->positionSalesRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Sales Rep.',
        ]);

        $this->positionTravelingSalesRepresentative = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Traveling Sales Representative',
        ]);

        $this->positionSeniorAccountant = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Senior Accountant',
        ]);

        $this->positionHeadOfAccounting = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Head of Accounting',
        ]);

        $this->positionAccountant = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Accountant',
        ]);

        $this->positionHRRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'H.R Rep',
        ]);

        $this->positionReceptionist = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Receptionist',
        ]);

        $this->positionCustomerServiceRepresentative = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Customer Service Representative',
        ]);

        $this->positionSupplierRelationsRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Supplier Relations Rep.',
        ]);

        $this->positionQualityAssurance = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Quality Assurance',
        ]);

        $this->positionWarehouseForeman = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Warehouse Foreman',
        ]);

        $this->positionWarehouseStaff = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'title' => 'Warehouse Staff',
        ]);
    }

    private function createTeams(): void
    {
        $this->info('☐ Create teams');

        $this->teamManagement = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Management',
        ]);

        $this->teamSales = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Sales',
        ]);

        $this->teamAccounting = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Accounting',
        ]);

        $this->teamHumanResources = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Human Resources',
        ]);

        $this->teamReception = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Reception',
        ]);

        $this->teamProductOversight = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Product Oversight',
        ]);

        $this->teamWarehouse = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'name' => 'Warehouse',
        ]);

        $this->teams = Team::get();
    }

    private function addTeamDescriptions(): void
    {
        $this->info('☐ Add team descriptions');

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamManagement->id,
            'description' => 'We are here to manage the entire company, set a vision and make sure we all go to the right direction, efficiently.',
        ]);

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamSales->id,
            'description' => 'Sales is responsible to make sure we sell the paper we have. Located in building B2.',
        ]);

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamAccounting->id,
            'description' => 'We deal with everything related to finances and accounting questions. Have a question? don’t hesitate to drop by say hi!',
        ]);

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamHumanResources->id,
            'description' => 'Holidays, timesheets, pay, bonuses. This is what we do.',
        ]);

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamReception->id,
            'description' => 'We are in charge of welcoming visitors, clients and families. Happy to always help!',
        ]);

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamProductOversight->id,
            'description' => 'In charge of all the new products we will release in the future.',
        ]);

        (new SetTeamDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamWarehouse->id,
            'description' => 'We are team in building A, responsible to ship paper to our beloved customers.',
        ]);
    }

    private function createEmployees(): void
    {
        $this->info('☐ Add employees');

        // create Jan Levinson as Michael's boss
        $this->jan = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Jan',
            'last_name' => 'Levinson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'World best boss of the best boss.';
        $this->addSpecificDataToEmployee($this->jan, $description, $this->pronounSheHer, $this->teamManagement, $this->employeeStatusFullTime, $this->positionRegionalManager, '1970-01-20');

        $description = 'World best boss. Or so they say.';
        $this->addSpecificDataToEmployee($this->michael, $description, $this->pronounHeHim, $this->teamManagement, $this->employeeStatusFullTime, $this->positionRegionalManager, '1965-03-15', $this->jan, $this->teamManagement);

        // assign Michael to another team right here
        (new AddEmployeeToTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $this->michael->id,
            'team_id' => $this->teamAccounting->id,
        ]);

        $this->dwight = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'At day I’m the best assistant regional manager there is. At night, I’m a seasoned beet farmer and a host of a wonderful AirBnB.';
        $this->addSpecificDataToEmployee($this->dwight, $description, $this->pronounHeHim, $this->teamManagement, $this->employeeStatusFullTime, $this->positionAssistantToTheRegionalManager, '1970-01-20', $this->michael);

        $this->phyllis = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Phyllis',
            'last_name' => 'Vance',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Wife of the wonderful Bob Vance, who has a great company about fridges - come visit his stores in Scranton.';
        $this->addSpecificDataToEmployee($this->phyllis, $description, $this->pronounSheHer, $this->teamSales, $this->employeeStatusFullTime, $this->positionRegionalDirectorOfSales, '1965-07-10', $this->michael, $this->teamSales);

        $this->jim = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Jim',
            'last_name' => 'Halpert',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I dream to be one day as good as my mentor, friend and eternal nemesys Dwight. Also, I have a wife and 3 beautiful children.';
        $this->addSpecificDataToEmployee($this->jim, $description, $this->pronounHeHim, $this->teamSales, $this->employeeStatusFullTime, $this->positionSalesRep, '1978-10-01', $this->phyllis);

        $this->kelly = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Kelly',
            'last_name' => 'Kapoor',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Did you know that Brad Pitt will be back with Jenifer Aniston in a few years? You’ve read it here first.';
        $this->addSpecificDataToEmployee($this->kelly, $description, $this->pronounSheHer, $this->teamProductOversight, $this->employeeStatusFullTime, $this->positionSalesRep, '1980-02-05', $this->michael, $this->teamProductOversight);

        $this->angela = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Angela',
            'last_name' => 'Martin',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I love cats and I have 24 of them in my house. I also think the workplace should be all about integrity and office romance.';
        $this->addSpecificDataToEmployee($this->angela, $description, $this->pronounSheHer, $this->teamAccounting, $this->employeeStatusFullTime, $this->positionHeadOfAccounting, '1974-11-11', $this->michael, $this->teamAccounting);

        $this->oscar = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Oscar',
            'last_name' => 'Martinez',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Bike lover, huge Trivial Pursuit enthusiast and avid book reader.';
        $this->addSpecificDataToEmployee($this->oscar, $description, $this->pronounTheyThem, $this->teamAccounting, $this->employeeStatusFullTime, $this->positionSeniorAccountant, '1972-03-08', $this->angela);

        $this->dakota = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Dakota',
            'last_name' => 'Johnson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Life is what we do of it --- Ophrah, 2020';
        $this->addSpecificDataToEmployee($this->dakota, $description, $this->pronounSheHer, $this->teamAccounting, $this->employeeStatusFullTime, $this->positionAccountant, null, $this->angela);

        $this->toby = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Toby',
            'last_name' => 'Flenderson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I have a daughter, Sasha, and a master’s degree in social work from Temple University.';
        $this->addSpecificDataToEmployee($this->toby, $description, $this->pronounTheyThem, $this->teamHumanResources, $this->employeeStatusFullTime, $this->positionHRRep, '1963-02-22', $this->michael, $this->teamHumanResources);

        $this->kevin = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Kevin',
            'last_name' => 'Malone',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Former Poker world champion, 6x times in a row, then I lost everything.';
        $this->addSpecificDataToEmployee($this->kevin, $description, $this->pronounHeHim, $this->teamAccounting, $this->employeeStatusPartTime, $this->positionAccountant, '1968-06-01', $this->angela);

        $this->erin = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Erin',
            'last_name' => 'Hannon',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I used to work at a Taco Bell Express, but I really prefer working at Dunder Mifflin.';
        $this->addSpecificDataToEmployee($this->erin, $description, $this->pronounSheHer, $this->teamReception, $this->employeeStatusFullTime, $this->positionReceptionist, '1986-05-01', $this->michael);

        $this->pete = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Pete',
            'last_name' => 'Miller',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Lover of baseball, basketball, football and everything that ends with balls.';
        $this->addSpecificDataToEmployee($this->pete, $description, $this->pronounHeHim, $this->teamProductOversight, $this->employeeStatusPartTime, $this->positionCustomerServiceRepresentative, null, $this->kelly);

        $this->meredith = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Meredith',
            'last_name' => 'Palmer',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I have an unfortunate habit of suffering various misadventures. I’ve contracted herpes, been hit by Michael’s car, had my pelvis broken, had my hair set on fire, caught head lice, and been bitten by a bat, a rat, and a raccoon, all on separate occasions, and had to get rabies post-exposure treatment';
        $this->addSpecificDataToEmployee($this->meredith, $description, $this->pronounSheHer, $this->teamProductOversight, $this->employeeStatusConsultant, $this->positionSupplierRelationsRep, '1959-11-12', $this->kelly);

        $this->val = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Val',
            'last_name' => 'Johnson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I don’t have anything interesting to say, as I’m a secondary character of the show.';
        $this->addSpecificDataToEmployee($this->val, $description, $this->pronounSheHer, $this->teamWarehouse, $this->employeeStatusFullTime, $this->positionWarehouseForeman, null, $this->michael, $this->teamWarehouse);

        $this->nate = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Nate',
            'last_name' => 'Troyat',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Michael Jordan was the best. Period.';
        $this->addSpecificDataToEmployee($this->nate, $description, $this->pronounHeHim, $this->teamWarehouse, $this->employeeStatusFullTime, $this->positionWarehouseStaff, null, $this->val);

        $this->glenn = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Glenn',
            'last_name' => 'Scott',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I am Glenn, and I approve this message.';
        $this->addSpecificDataToEmployee($this->glenn, $description, $this->pronounHeHim, $this->teamWarehouse, $this->employeeStatusConsultant, $this->positionWarehouseStaff, null, $this->val);

        $this->philip = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Philip',
            'last_name' => 'Scott',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I lived in Chicago most of my life and have 2 children.';
        $this->addSpecificDataToEmployee($this->philip, $description, $this->pronounHeHim, $this->teamWarehouse, $this->employeeStatusFullTime, $this->positionWarehouseStaff, null, $this->val);
    }

    private function addAvatars(): void
    {
        $this->info('☐ Add avatars of employees');

        $this->michael->avatar_file_id = $this->createAvatar('9952eaee-269b-4e3c-b41d-0f613d4128c3')->id;
        $this->michael->save();
        $this->toby->avatar_file_id = $this->createAvatar('97502e6a-0898-44e0-afd2-9476d76894ce')->id;
        $this->toby->save();
        $this->jim->avatar_file_id = $this->createAvatar('fb76ac9c-ece5-49df-a519-3fc18a71602f')->id;
        $this->jim->save();
        $this->erin->avatar_file_id = $this->createAvatar('d829a8c1-cc01-4b03-ac6d-a997f48d0e6f')->id;
        $this->erin->save();
        $this->kelly->avatar_file_id = $this->createAvatar('14a6d0a0-1f95-47ea-965c-39405e72d913')->id;
        $this->kelly->save();
        $this->jan->avatar_file_id = $this->createAvatar('7e10ffe0-c6f8-481b-b96b-be7a579813b3')->id;
        $this->jan->save();
        $this->kevin->avatar_file_id = $this->createAvatar('9916c3d3-f099-426f-9d1c-d8e4e7c87edd')->id;
        $this->kevin->save();
        $this->angela->avatar_file_id = $this->createAvatar('1d1e5b14-1382-45fa-8e08-b4daa01b0908')->id;
        $this->angela->save();
        $this->oscar->avatar_file_id = $this->createAvatar('2ad68247-a0a5-4bda-9db0-839d3e40c2c7')->id;
        $this->oscar->save();
        $this->meredith->avatar_file_id = $this->createAvatar('8dbdff2c-be0d-4a02-ae0a-a39656820dd9')->id;
        $this->meredith->save();
        $this->dwight->avatar_file_id = $this->createAvatar('c1eab52a-1b79-4536-a2aa-e821605f92d2')->id;
        $this->dwight->save();
        $this->phyllis->avatar_file_id = $this->createAvatar('a95cbf7b-9f28-438a-a1fc-8c8d93be4939')->id;
        $this->phyllis->save();
        $this->dakota->avatar_file_id = $this->createAvatar('6113654a-be8a-4829-90f4-0c7aa2a5469c')->id;
        $this->dakota->save();
    }

    private function createFutureEmployees(): void
    {
        $this->info('☐ Add employees that will be hired in two days');

        $this->debra = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Debra',
            'last_name' => 'Filzgetard',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $this->addSpecificDataToEmployee($this->debra, null, $this->pronounSheHer, $this->teamManagement, $this->employeeStatusFullTime, $this->positionAssistantToTheRegionalManager, '1970-01-20');

        Employee::where('id', $this->debra->id)->update([
            'hired_at' => Carbon::now()->addDay(),
        ]);

        $this->employees = Employee::all();
    }

    private function addSpecificDataToEmployee(Employee $employee, ?string $description, Pronoun $pronoun, Team $team, EmployeeStatus $status, Position $position, string $birthdate = null, Employee $manager = null, Team $leaderOfTeam = null): void
    {
        (new AddEmployeeToTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        (new AssignPronounToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $employee->id,
            'pronoun_id' => $pronoun->id,
            'team_id' => $team->id,
        ]);

        (new AssignEmployeeStatusToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $employee->id,
            'employee_status_id' => $status->id,
        ]);

        (new AssignPositionToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $employee->id,
            'position_id' => $position->id,
        ]);

        if ($birthdate) {
            $date = Carbon::parse($birthdate);
            (new SetBirthdate)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'employee_id' => $employee->id,
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ]);
        }

        if ($manager) {
            (new AssignManager)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'employee_id' => $employee->id,
                'manager_id' => $manager->id,
            ]);
        }

        if ($leaderOfTeam) {
            (new SetTeamLead)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'employee_id' => $employee->id,
                'team_id' => $leaderOfTeam->id,
            ]);
        }

        if ($description) {
            (new SetPersonalDescription)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'employee_id' => $employee->id,
                'description' => $description,
            ]);
        }

        $date = $this->faker->dateTimeThisDecade();
        (new SetHiringDate)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $employee->id,
            'year' => (int) $date->format('Y'),
            'month' => (int) $date->format('m'),
            'day' => (int) $date->format('d'),
        ]);
    }

    private function addSkills(): void
    {
        $this->info('☐ Assign skills to employees');

        $skills = collect([]);
        $skills->push([
            'employee' => $this->michael->id,
            'skills' => [
                'management',
                'people skill',
            ],
        ]);
        $skills->push([
            'employee' => $this->dwight->id,
            'skills' => [
                'sales techniques',
                'management',
                'tv shows',
                'paper',
                'as 400',
            ],
        ]);
        $skills->push([
            'employee' => $this->phyllis->id,
            'skills' => [
                'fridges',
                'paper',
                'party planning',
                'scranton',
            ],
        ]);
        $skills->push([
            'employee' => $this->jim->id,
            'skills' => [
                'people skill',
                'paper',
                'pranks',
            ],
        ]);
        $skills->push([
            'employee' => $this->kelly->id,
            'skills' => [
                'sales',
                'party planning',
            ],
        ]);
        $skills->push([
            'employee' => $this->angela->id,
            'skills' => [
                'party planning',
                'php',
                'siemens a6748',
            ],
        ]);
        $skills->push([
            'employee' => $this->oscar->id,
            'skills' => [
                'accounting',
                'expenses',
                'bike',
            ],
        ]);
        $skills->push([
            'employee' => $this->dakota->id,
            'skills' => [
                'accounting',
                'php',
                'mysql',
            ],
        ]);
        $skills->push([
            'employee' => $this->toby->id,
            'skills' => [
                'expenses',
                'holiday',
                'time off',
                'as 400',
            ],
        ]);
        $skills->push([
            'employee' => $this->kevin->id,
            'skills' => [
                'accounting',
                'expenses',
            ],
        ]);
        $skills->push([
            'employee' => $this->erin->id,
            'skills' => [
                'reception',
                'contacts',
                'directory',
            ],
        ]);
        $skills->push([
            'employee' => $this->pete->id,
            'skills' => [
                'accounting',
                'paper',
                'as 400',
            ],
        ]);
        $skills->push([
            'employee' => $this->meredith->id,
            'skills' => [
                'accounting',
                'expenses',
                'party planning',
            ],
        ]);
        $skills->push([
            'employee' => $this->val->id,
            'skills' => [
                'accounting',
            ],
        ]);
        $skills->push([
            'employee' => $this->nate->id,
            'skills' => [
                'party planning',
                'eco fees',
                'as 400',
            ],
        ]);
        $skills->push([
            'employee' => $this->glenn->id,
            'skills' => [
                'target',
                'expenses',
                'samsung 45kxs',
            ],
        ]);
        $skills->push([
            'employee' => $this->philip->id,
            'skills' => [
                'product management',
                'user experience',
                'ui',
            ],
        ]);

        foreach ($skills as $skill) {
            foreach ($skill['skills'] as $individualSkill) {
                (new AttachEmployeeToSkill)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $skill['employee'],
                    'name' => $individualSkill,
                ]);
            }
        }
    }

    private function addWorkFromHomeEntries(): void
    {
        $this->info('☐ Add work from home information (this might take some time)');

        foreach ($this->employees as $employee) {
            $twoYearsAgo = Carbon::now()->subYears(2);
            while (! $twoYearsAgo->isTomorrow()) {
                if ($twoYearsAgo->isSaturday() || $twoYearsAgo->isSunday()) {
                    $twoYearsAgo->addDay();
                    continue;
                }

                if (rand(1, 3) != 1) {
                    $twoYearsAgo->addDay();
                    continue;
                }

                (new UpdateWorkFromHomeInformation)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'date' => $twoYearsAgo->format('Y-m-d'),
                    'work_from_home' => true,
                ]);

                $twoYearsAgo->addDay();
            }
        }
    }

    private function createQuestions(): void
    {
        $this->info('☐ Add questions to know employees better');

        $this->questionWhatIsYourFavoriteAnimal = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => false,
            'title' => 'What is your favorite animal?',
        ]);

        $this->questionWhatIsTheBestMovieYouHaveSeenThisYear = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => false,
            'title' => 'What is the best movie you have seen this year?',
        ]);

        $this->questionCareToShareYourBestRestaurantInTown = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => false,
            'title' => 'Care to share your best restaurant in town?',
        ]);

        $this->questionWhatIsYourFavoriteBand = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => false,
            'title' => 'What is your favorite band?',
        ]);

        $this->questionWhatAreTheCurrentHighlightsOfThisYearForYou = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => false,
            'title' => 'What are the current highlights of this year for you?',
        ]);

        $this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => false,
            'title' => 'Do you have any personal goals that you would like to share with us this week?',
        ]);

        $this->questionWhatIsTheBestTVShowOfThisYearSoFar = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'active' => true,
            'title' => 'What is the best TV show of this year so far?',
        ]);
    }

    private function addAnswers(): void
    {
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->michael, 'I love cats and dogs equally, but really, I prefer dogs as cats just want to murder us.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->dwight, 'The best animal is the one that tastes best on my plate.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->phyllis, 'I love dolphins as they are beautiful, graceful and nice to other animals - except sharks.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->jim, 'Dogs. Friendly, nice, with only one master, like Dwight.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->kelly, 'What’s the favorite animal of Brad Pitt?');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->angela, 'Cats. The more, the better.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->oscar, 'I love dogs. They are friendly and nice.');

        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->michael, 'Horrible bosses 2');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->dwight, 'Project power');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->phyllis, 'The tax collector');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->jim, 'Work it');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->kelly, '365 dnl');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->angela, 'Tenet');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->oscar, 'Mulan');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->dakota, 'An american Pickle');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->toby, 'Tenet');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->kevin, 'The old guard');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->erin, 'Hamilton');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->pete, 'Palm Springs');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->meredith, 'Tenet');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->val, 'USS Greyhound');
        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->nate, 'Enraged');

        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->michael, 'I suggest to go to the Korean restaurant called Korean Meat Harbor. It is absolutely incredible.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->dwight, 'I don’t go to restaurant. Restaurants come to me for inspiration.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->jim, 'Pam and I love McDonalds.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->kelly, 'I prefer to stay at home with Ryan.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->angela, 'The senator and me love to go to Carmel Palace. It’s fancy enough for the both of us.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->oscar, 'Best cuisine in town: of course the french restaurant on main street. Lots of finesse there.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->val, 'Great burger king on Maclom Street.');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->nate, 'Excellent shisha excellent service excellent view. The actual shisha are very smooth and the drinks on the menu complement them perfectly. Best place I’ve visited in a while!');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->glenn, 'Shake shake: Amazing food and service, scenery absolutely Beautiful at night! Service really good and efficient, waiters friendly and helpful');
        $this->writeAnswer($this->questionCareToShareYourBestRestaurantInTown, $this->philip, 'IStanbul cafe: This is my secont time in this cafe and I really enjoyed every second.The atmosphere is great,location,food and drinks as well and,last but not least,staff is very helpful');

        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->michael, 'Beatles. Deserving of being ranked as the most influential pop group of all time, the Beatles hold a different place in history than all other boy bands.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->dwight, 'The Jackson 5 because Michael Jackson.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->phyllis, 'New Kids on the Block!!!!!!');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->jim, 'I love love love Take That mainly because of Robbie Williams');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->kelly, 'Backstreet Boys. All their songs are hits and remain in everybody’s heart.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->angela, '98 Degrees - . Unlike a number of other boy bands of the time, the group members wrote much of their own material and aimed to differentiate themselves from their competition.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->oscar, 'Arashi - The Japanese translation for the word Arashi is "Storm." The triple-platinum single "Calling/Breathless" was one of the biggest Japanese hit singles of 2013. Arashi has sold more than 30 million records worldwide.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->dakota, 'I love rap and 2Pac is probably the best rapper that ever existed.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->toby, 'Korean pop is something that I think you should hear at least once. My favorite band is TVXQ.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->kevin, 'Jonas Brothers');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->erin, 'Big Time Rush. In a similar fashion to the Monkees more than 40 years before, Big Time Rush is a band that came together specifically to star in a TV series.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->pete, 'Oasis 😀 Oasis once turned the UK music industry upside down - and a reunion of Liam and Noel Gallagher is on a lot of people’s wish lists.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->meredith, 'The only great american band: American Idiot reinvented the Californian punks and made a whole new generation love them. I am talking about Green Day.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->val, 'As influential as ever, Joy Division’s dark sound remains as haunting now as it did in the post-punk years.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->nate, 'As they call themselves, they ARE the greatest rock ’n’ roll band in the world. Mick Jagger and Keith Richards are the quintessential rock duo.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->glenn, 'Celine Dion, beacuse of her amazing voice.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteBand, $this->philip, 'Trump.');

        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->michael, 'Office Olympics were absolutely awesome. I love my team and I love being a boss.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->jim, 'When my beautiful wife Pam told me we were expecting our second child. This was so moving.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->kelly, 'When Ryan told me he loved me for the fourth time before asking me to lend him 400$.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->angela, 'I’ve adopted my fourth cat of the year and it was great.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->oscar, 'I had so much fun going in Peru for two weeks. Lots of amazing people, really friendly.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->dakota, 'The AS 340 project was a really great experience for me.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->toby, 'When Pam got hired. This brightened my day.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->kevin, 'The fourth concert I did at a wedding. So much emotion.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->erin, 'I loved when I travelled to Australia.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->pete, 'The end of the SOHO project.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->meredith, 'When I got out of rehab the fourth time this year. This was an amazing achievement for me.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->val, 'Receiving our third container in a two streak days... this was amazing.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->nate, 'Having the child of my sister in my arms was a defining experience and the source of a great joy that I hope will last forever.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->glenn, 'Finishing World of warcraft for the fourth time this year.');
        $this->writeAnswer($this->questionWhatAreTheCurrentHighlightsOfThisYearForYou, $this->philip, 'I love chewing gums.');

        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->michael, 'Not being late this week.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->dwight, 'Arrive at 4am every day.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->phyllis, 'Finalize the quarterly report on Friday at noon.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->jim, 'Successfully prank Dwight every day.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->kelly, 'Going out with Ryan and let him pay for once.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->angela, 'Try to buy my fourth cat this year.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->oscar, 'Trying out the new french restaurant.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->dakota, 'Increase sales of 43% compared to last week.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->toby, 'Filing a report about one of my manager being abusive.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->kevin, 'Going to the gym for the first time in 67 years on Wednesday.');
        $this->writeAnswer($this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek, $this->erin, 'Taking the lecture about being a better secretary.');

        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->dwight, 'The Office');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->jim, 'The Office');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->angela, 'Dawson Creek');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->dakota, 'Superman, the TV show');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->toby, 'Santa Barbara');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->kevin, '6 feet under');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->erin, 'The Mechanic');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->pete, 'Henri 56 Ninja');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->meredith, 'Fort Boyard');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->glenn, 'Beverly Hills');
        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->philip, 'Dirty Harry ');

        $this->info('☐ Writing answers for each question');
    }

    private function writeAnswer(Question $question, Employee $employee, string $answer): void
    {
        (new CreateAnswer)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $employee->id,
            'question_id' => $question->id,
            'body' => $answer,
        ]);
    }

    private function addWorklogEntriesAndMorale(): void
    {
        $this->info('☐ Write work log entries on behalf of employees (that might take some time)');

        $worklogs = collect([
            'Planning party comity day. It was great, we tried to create a birthday theme for Angela. I think she loved it',
            'Tried to make our biggest sale of the year, but it did not work unfortunately.',
            'The supercomputer tried to destroy our sales for the year, but we did not let it win. Dwight is super happy.',
            '5 letters sent today. New contracts coming. Also I tried to finalize our integration with Excel.',
            'Called 3 potential clients today. One of them is super interested. Will try again tomorrow.',
            'Everybody is dressed up for Halloween, but unfortunately Michael has to fire somebody.',
            'The Dunder-Mifflin crew goes on a "motivational" cruise to Lake Wallenpaupack. A drunken Roy is inspired to announce a date for his wedding with Pam. Jim is crushed and confesses to Michael his feelings for Pam.',
            'Michael is aggravated that his birthday isn’t getting more attention than Kevin’s skin cancer test.',
            'Michael converts the warehouse into a casino for a charity casino night, but ends up with two dates - Jan and his realtor, Carol. Jim has something to tell Pam.',
            'The Dunder Mifflin Infinity website is launching and Michael is excited about going to the big launch party in New York while Angela plans a satellite party for the Scranton branch. Meanwhile, Dwight competes against the website to see who can sell the most paper in one day.',
        ]);

        $twoYearsAgo = Carbon::now()->subYears(2);
        while (! $twoYearsAgo->isSameDay(Carbon::now())) {
            if ($twoYearsAgo->isSaturday() || $twoYearsAgo->isSunday()) {
                $twoYearsAgo->addDay();
                continue;
            }

            if (rand(1, 3) != 1 && $twoYearsAgo->diffInDays(Carbon::now()) >= 7) {
                $twoYearsAgo->addDay();
                continue;
            }

            foreach ($this->employees as $employee) {
                (new LogWorklog)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'content' => $worklogs->random(),
                    'date' => $twoYearsAgo->format('Y-m-d'),
                ]);

                (new LogMorale)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'emotion' => rand(1, 3),
                    'comment' => rand(1, 5) == 1 ? 'That was one of these days' : null,
                    'date' => $twoYearsAgo->format('Y-m-d'),
                ]);
            }

            // dispatch team morale
            LogTeamsMorale::dispatch($twoYearsAgo);

            $twoYearsAgo->addDay();
        }
    }

    private function addExpenses(): void
    {
        $this->info('☐ Write expenses on behalf of employees');

        $reasons = collect([
            'Invitation to restaurant',
            'Laptop upgrade',
            'Hotel Toronto 2 nights',
            'Flight LA -> Bangkok',
            'Metro card',
            'Sport yearly subscription',
            'Internet at home',
            'New screen protector',
            'Tools',
            'Book',
        ]);

        $categories = collect([
            $this->expenseCategoryMaintenanceAndRepairs,
            $this->expenseCategoryMealsAndEntertainment,
            $this->expenseCategoryOfficeExpense,
            $this->expenseCategoryTravel,
            $this->expenseCategoryMotorVehicleExpenses,
        ]);

        foreach ($this->employees as $employee) {
            if (rand(1, 2) == 1) {
                continue;
            }

            foreach ($reasons as $reason) {
                if (rand(1, 3) == 1) {
                    continue;
                }

                (new CreateExpense)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'expense_category_id' => $categories->shuffle()->first()->id,
                    'title' => $reason,
                    'amount' => rand(891, 45945),
                    'currency' => 'USD',
                    'expensed_at' => $this->faker->dateTimeThisYear()->format('Y-m-d'),
                ]);
            }
        }
    }

    private function createHardware(): void
    {
        $this->info('☐ Add hardware and associate them to employees');

        $hardware = collect([
            'Macbook Air 2019',
            'Dell XPS Pro',
            'Logitech G Pro Gaming',
            'Corsair Graphite 230T',
            'Audio-Technica AT2020',
            'Logitech C920',
            'BenQ XL2540',
        ]);

        foreach ($this->employees as $employee) {
            if (rand(1, 2) == 1 && $employee->id != $this->michael->id) {
                continue;
            }

            foreach ($hardware as $item) {
                if (rand(1, 3) == 1) {
                    continue;
                }

                $newlyItem = (new CreateHardware)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'name' => $item,
                    'serial_number' => $this->faker->swiftBicNumber,
                ]);

                (new LendHardware)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'hardware_id' => $newlyItem->id,
                ]);
            }
        }

        // also create a bunch of unused hardware
        for ($i = 0; $i < 7; $i++) {
            (new CreateHardware)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'name' => $hardware->shuffle()->first(),
                'serial_number' => $this->faker->swiftBicNumber,
            ]);
        }
    }

    private function createSoftware(): void
    {
        $this->info('☐ Add software and associate them to employees');

        $softwares = collect([
            'Office 365',
            'Sketch',
            'Adobe Reader',
            'Sublime Text 4',
            'Powerpoint',
            'Zoom',
            'Teams',
        ]);

        foreach ($softwares as $item) {
            $newlyItem = (new CreateSoftware)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'name' => $item,
                'seats' => rand(3, 30),
                'product_key' => $this->faker->uuid,
            ]);

            foreach ($this->employees as $employee) {
                if (rand(1, 2) == 1) {
                    continue;
                }

                (new GiveSeatToEmployee)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'software_id' => $newlyItem->id,
                ]);
            }
        }
    }

    private function addRecentShips(): void
    {
        $this->info('☐ Add recent ships entries for teams');

        (new CreateShip)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'team_id' => $this->teamManagement->id,
            'title' => 'New overall organization',
            'description' => 'We are proud to have completely updated.',
            'employees' => [
                $this->michael->id, $this->dwight->id,
            ],
        ]);
    }

    private function addRateYourManagerSurveys(): void
    {
        $this->info('☐ Add rate your manager current and past surveys');

        $managers = $this->company->getListOfManagers();

        $potentialAnswers = collect([
            'I think you should be more transparent with your team.',
            'We have a healthy relationship but you tend to take decisions without consulting your team, and I think it’s not ideal for the general atmosphere of the team.',
            'Perhaps you could take the 1 on 1 more seriously or at least give your team mates at least 1 hour per week of dedicated time. We like having you around but you need to hear more from us.',
            'Excellent visionary, excellent interpersonal skills and we think you are the best manager we have ever had, by far and we hope that you will stay for at least forever',
        ]);

        // create 5 fake previous surveys
        // as this process relies heavily on crons, it will be tough to call
        // the service to create surveys, as they have control in place everywhere
        // to make sure we can’t mess with them and update past surveys.
        for ($i = 0; $i < 5; $i++) {
            foreach ($managers as $manager) {
                $dateOfSurvey = Carbon::now()->subMonths(5 - $i);
                $survey = RateYourManagerSurvey::create([
                    'manager_id' => $manager->id,
                    'active' => false,
                    'valid_until_at' => $dateOfSurvey->addDays(3)->format('Y-m-d'),
                    'created_at' => $dateOfSurvey->format('Y-m-d'),
                ]);

                $employees = $manager->getListOfDirectReports();
                foreach ($employees as $employee) {
                    $rating = RateYourManagerAnswer::BAD;
                    switch (rand(1, 3)) {
                        case 1:
                            $rating = RateYourManagerAnswer::BAD;
                            break;
                        case 2:
                            $rating = RateYourManagerAnswer::AVERAGE;
                            break;
                        case 3:
                            $rating = RateYourManagerAnswer::GOOD;
                            break;
                    }

                    RateYourManagerAnswer::create([
                        'rate_your_manager_survey_id' => $survey->id,
                        'employee_id' => $employee->id,
                        'active' => false,
                        'rating' => $rating,
                        'comment' => rand(1, 2) == 1 ? $potentialAnswers->random() : null,
                        'reveal_identity_to_manager' => false,
                    ]);
                }
            }
        }

        // create one active survey
        foreach ($managers as $manager) {
            $survey = RateYourManagerSurvey::create([
                'manager_id' => $manager->id,
                'active' => true,
                'valid_until_at' => Carbon::now()->addDays(3),
            ]);

            $employees = $manager->getListOfDirectReports();
            foreach ($employees as $employee) {
                RateYourManagerAnswer::create([
                    'rate_your_manager_survey_id' => $survey->id,
                    'employee_id' => $employee->id,
                    'active' => true,
                    'rating' => null,
                    'comment' => null,
                    'reveal_identity_to_manager' => false,
                ]);
            }
        }
    }

    private function addOneOnOnes(): void
    {
        $this->info('☐ Add one on one entries');

        $talkingPoints = collect([
            'What can I do to accelerate my career development?',
            'What is your vision for our team?',
            'What have you done with customer 3029?',
            'Do you enjoy working with Sue Helen?',
            'Do you think you can achieve your monthly goal?',
            'Reorganisation of the marketing department',
            'Christmas Party',
            'Do you expect to take a time off later this year?',
            'Do you think Dwight should become a manager?',
        ]);

        $actionItems = collect([
            'Follow up with Jan',
            'Send slides to Michael',
            'Update goals for Q2',
            'Clean your agenda and organize all department emails',
            'Prepare presentation for August Seminar',
            'Set a meeting with warehouse',
            'Call Michael daily',
            'Send Q4 goals to Jan',
            'Prepare September',
        ]);

        $notes = collect([
            'I suggest you to watch Season 3 and 4 of The Office',
            'Recommended read: optimize sale conversions',
            'Note for future you: read more',
            'Awesome and productive one on one.',
            'We need to do those one on ones more often - ideally twice per month.',
        ]);

        foreach ($this->employees as $employee) {
            if (rand(1, 2) == 1 && $employee->id != $this->michael->id) {
                continue;
            }

            $manager = $employee->getListOfManagers()->first();

            if ($manager) {
                $date = CarbonImmutable::now()->subDays(150);

                // create a first entry with a couple of points and items
                // this entire process is complex because each entry depends on
                // the previous entry, so we need to simulate this in the
                // setup process
                $entry = (new CreateOneOnOneEntry)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'manager_id' => $manager->id,
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                ]);

                // add talking points
                for ($j = 0; $j < rand(2, 6); $j++) {
                    $randomItem = $talkingPoints->shuffle()->first();

                    $talkingPoint = (new CreateOneOnOneTalkingPoint)->execute([
                        'company_id' => $this->company->id,
                        'author_id' => $this->michael->id,
                        'one_on_one_entry_id' => $entry->id,
                        'description' => $randomItem,
                    ]);

                    if (rand(1, 2) == 1) {
                        (new ToggleOneOnOneTalkingPoint)->execute([
                            'company_id' => $this->company->id,
                            'author_id' => $this->michael->id,
                            'one_on_one_entry_id' => $entry->id,
                            'one_on_one_talking_point_id' => $talkingPoint->id,
                        ]);
                    }
                }

                // add action items
                for ($j = 0; $j < rand(2, 6); $j++) {
                    $randomItem = $actionItems->shuffle()->first();

                    $actionItem = (new CreateOneOnOneActionItem)->execute([
                        'company_id' => $this->company->id,
                        'author_id' => $this->michael->id,
                        'one_on_one_entry_id' => $entry->id,
                        'description' => $randomItem,
                    ]);

                    if (rand(1, 2) == 1) {
                        (new ToggleOneOnOneActionItem)->execute([
                            'company_id' => $this->company->id,
                            'author_id' => $this->michael->id,
                            'one_on_one_entry_id' => $entry->id,
                            'one_on_one_action_item_id' => $actionItem->id,
                        ]);
                    }
                }

                // add notes
                for ($j = 0; $j < rand(1, 3); $j++) {
                    $randomItem = $notes->shuffle()->first();

                    (new CreateOneOnOneNote)->execute([
                        'company_id' => $this->company->id,
                        'author_id' => $this->michael->id,
                        'one_on_one_entry_id' => $entry->id,
                        'note' => $randomItem,
                    ]);
                }

                // now we mark the entry as "happened", so it will create a new
                // entry
                for ($i = 0; $i < 9; $i++) {
                    $date = $date->copy()->addDays(7);

                    $entry = (new MarkOneOnOneEntryAsHappened)->execute([
                        'company_id' => $this->company->id,
                        'author_id' => $this->michael->id,
                        'one_on_one_entry_id' => $entry->id,
                        'date' => $date->format('Y-m-d'),
                    ]);

                    for ($j = 0; $j < rand(2, 6); $j++) {
                        $randomItem = $talkingPoints->shuffle()->first();

                        $talkingPoint = (new CreateOneOnOneTalkingPoint)->execute([
                            'company_id' => $this->company->id,
                            'author_id' => $this->michael->id,
                            'one_on_one_entry_id' => $entry->id,
                            'description' => $randomItem,
                        ]);

                        if (rand(1, 2) == 1) {
                            (new ToggleOneOnOneTalkingPoint)->execute([
                                'company_id' => $this->company->id,
                                'author_id' => $this->michael->id,
                                'one_on_one_entry_id' => $entry->id,
                                'one_on_one_talking_point_id' => $talkingPoint->id,
                            ]);
                        }
                    }

                    for ($j = 0; $j < rand(2, 6); $j++) {
                        $randomItem = $actionItems->shuffle()->first();

                        $actionItem = (new CreateOneOnOneActionItem)->execute([
                            'company_id' => $this->company->id,
                            'author_id' => $this->michael->id,
                            'one_on_one_entry_id' => $entry->id,
                            'description' => $randomItem,
                        ]);

                        if (rand(1, 2) == 1) {
                            (new ToggleOneOnOneActionItem)->execute([
                                'company_id' => $this->company->id,
                                'author_id' => $this->michael->id,
                                'one_on_one_entry_id' => $entry->id,
                                'one_on_one_action_item_id' => $actionItem->id,
                            ]);
                        }
                    }

                    for ($j = 0; $j < rand(1, 3); $j++) {
                        $randomItem = $notes->shuffle()->first();

                        (new CreateOneOnOneNote)->execute([
                            'company_id' => $this->company->id,
                            'author_id' => $this->michael->id,
                            'one_on_one_entry_id' => $entry->id,
                            'note' => $randomItem,
                        ]);
                    }
                }
            }
        }
    }

    private function addProjects(): void
    {
        $this->info('☐ Add projects');

        $this->projectInfinity = (new CreateProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'project_lead_id' => $this->jim->id,
            'name' => 'Dunder Mifflin Infinity',
            'code' => 'dun-76',
            'short_code' => 'DUN',
            'summary' => 'Revitalize the company with new technology',
            'description' => 'We aim to replace all our old technology with something much more powerful: a website with a complete set of instructions. The goal is to replace the sales people by a machine learning algorithm.',
        ]);

        (new StartProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
        ]);

        (new CreateProjectLink)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'type' => 'url',
            'label' => 'Upcoming website',
            'url' => 'https://dundermifflin.com/infinity',
        ]);

        (new CreateProjectLink)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'type' => 'slack',
            'label' => 'Slack channel of the project',
            'url' => 'https://slack.com/infinity',
        ]);

        (new CreateProjectStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'status' => ProjectStatus::ON_TRACK,
            'title' => 'Phase 2 is completed',
            'description' => 'Yes, you have read it right. We have finally finished the second phase of the project, which makes us proud. We are on track with delivering the project at the promised date, and we will let you know how it is going.',
        ]);

        // assign members to the project
        (new AddEmployeeToProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'employee_id' => $this->dwight->id,
            'role' => 'Assistant to the project lead',
        ]);
        (new AddEmployeeToProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'employee_id' => $this->erin->id,
            'role' => 'Secretary',
        ]);
        (new AddEmployeeToProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'employee_id' => $this->oscar->id,
            'role' => 'Developer',
        ]);
        (new AddEmployeeToProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'employee_id' => $this->angela->id,
            'role' => 'Developer',
        ]);
        (new AddEmployeeToProject)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'project_id' => $this->projectInfinity->id,
            'employee_id' => $this->michael->id,
            'role' => null,
        ]);

        // add decisions to the project
        (new CreateProjectDecision)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'title' => 'Ryan will lead the project in the coming month',
            'decided_at' => '2019-03-12',
            'deciders' => [$this->michael->id],
        ]);
        (new CreateProjectDecision)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'title' => 'Overtime is permitted',
            'decided_at' => '2019-05-04',
            'deciders' => null,
        ]);
        (new CreateProjectDecision)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'title' => 'We will hire an agency for the website',
            'decided_at' => '2019-06-29',
            'deciders' => [$this->dwight->id, $this->oscar->id],
        ]);

        // add messages
        $messages = collect([
            'Let’s change how we do business',
            'Ryan promoted as the principal project manager',
            'Changing the name of the project - my thoughts',
            'Need more resources? Contact Corporate if you need help',
        ]);
        $content = 'Kelly tries to restart her relationship with Ryan, an effort he ignores until she (untruly) tells him she’s pregnant. He agrees to discuss the situation over dinner that night. Jim informs Pam that Dwight and Angela are secretly dating, only to discover that she has known this for quite some time. Meanwhile, Dwight attempts to make amends for the death of Angela’s cat Sprinkles by giving her a stray cat he found in his barn. Dwight’s cousin Mose named the cat Garbage because that’s what it eats. Angela rejects the gift, and Dwight attempts to dump the cat into the office of Vance Refrigeration.

Creed dyes his hair jet-black (using ink cartridges) in an attempt to convince everyone that he’s much younger. After a conversation with Jan, Michael decides to formally challenge Dunder Mifflin Infinity by claiming that Ryan is being ageist. Michael brings the octogenarian co-founder of Dunder Mifflin into a meeting to make his point about old things still being useful, but shoves Dunder out after tiring of his rambling stories. Angela asks Dwight out to dinner, where she breaks up with him, saying that she can’t look into Dwight’s eyes without seeing Sprinkles’ corpse.

* Alternate talking heads of different people reacting to Jim and Pam dating
* Michael drives a rental car to the office using a GPS. Jan calls him and yells at him for allegedly eating her Grape-Nuts';

        foreach ($messages as $message) {
            $message = (new CreateProjectMessage)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->jim->id,
                'project_id' => $this->projectInfinity->id,
                'title' => $message,
                'content' => $content,
            ]);

            if (rand(1, 2) == 1) {
                (new MarkProjectMessageasRead)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'project_id' => $this->projectInfinity->id,
                    'project_message_id' => $message->id,
                ]);
            }
        }

        // add tasks
        (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => null,
            'title' => 'Organize a meetup with HR',
            'description' => 'We need to make sure that HR is on par with what we want to achieve with this project.',
        ]);
        $task = (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => null,
            'title' => 'Migrate domain names when the new site launches',
            'description' => null,
        ]);
        (new AssignProjectTaskToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_id' => $task->id,
            'assignee_id' => $this->oscar->id,
        ]);

        $launchTaskList = (new CreateProjectTaskList)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'title' => 'Todos for the launch',
            'description' => 'Everything we need to make sure before the new site launches',
        ]);
        $task = (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => $launchTaskList->id,
            'title' => 'Make sure the SEO is implemented',
            'description' => null,
        ]);
        (new AssignProjectTaskToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_id' => $task->id,
            'assignee_id' => $this->angela->id,
        ]);
        (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->oscar->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => $launchTaskList->id,
            'title' => 'Check the Fathom Analytics code',
            'description' => null,
        ]);
        $task = (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => $launchTaskList->id,
            'title' => 'Migrate the ACLs',
            'description' => null,
        ]);
        (new AssignProjectTaskToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_id' => $task->id,
            'assignee_id' => $this->erin->id,
        ]);

        $marketingTaskList = (new CreateProjectTaskList)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'title' => 'Marketing assets to provide',
            'description' => null,
        ]);
        $task = (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->meredith->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => $marketingTaskList->id,
            'title' => 'Take appointment with the photographer',
            'description' => 'We need to make sure all photos look great if possible',
        ]);
        (new AssignProjectTaskToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_id' => $task->id,
            'assignee_id' => $this->oscar->id,
        ]);
        (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->oscar->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => $marketingTaskList->id,
            'title' => 'Update all business cards with the new URL',
            'description' => null,
        ]);
        (new CreateProjectTask)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->jim->id,
            'project_id' => $this->projectInfinity->id,
            'project_task_list_id' => $marketingTaskList->id,
            'title' => 'Migrate the ACLs',
            'description' => null,
        ]);
    }

    private function createTimeTrackingEntries(): void
    {
        $this->info('☐ Add time tracking entries');

        // create random time tracking entries for the project
        // we will create a lot of timesheets
        for ($weeksAgo = 0; $weeksAgo < 5; $weeksAgo++) {
            $this->populateTimeTrackingEntries($this->michael, $weeksAgo);
        }

        // create multiple time tracking entries for direct reports of Michael
        $allDirectReports = $this->michael->directReports;

        foreach ($allDirectReports as $directReport) {
            $employee = $directReport->directReport;

            for ($weeksAgo = 0; $weeksAgo < 5; $weeksAgo++) {
                $this->populateTimeTrackingEntries($employee, $weeksAgo);
            }
        }
    }

    private function populateTimeTrackingEntries(Employee $employee, int $weeksAgo): void
    {
        // loop over all existing project tasks and assign random times
        // first we need to create timesheets
        $startOfWeek = Carbon::now()->subWeeks($weeksAgo)->startOfWeek();
        $timesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $this->company->id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => $startOfWeek->format('Y-m-d'),
        ]);

        $allTasks = $this->projectInfinity->tasks;

        for ($day = 0; $day < 6; $day++) {
            $date = $startOfWeek->copy()->addDays($day);
            if ($date->isFuture()) {
                continue;
            }

            // taking 3 random tasks in the list of tasks of this project
            for ($taskNumber = 0; $taskNumber < 2; $taskNumber++) {
                $task = $allTasks->random();
                (new CreateTimeTrackingEntry)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'project_id' => $this->projectInfinity->id,
                    'project_task_id' => $task->id,
                    'duration' => rand(30, 180),
                    'date' => $date->format('Y-m-d'),
                    'description' => null,
                ]);
            }
        }

        // submit only older timesheets
        if ($weeksAgo > 2) {
            (new SubmitTimesheet)->execute([
                'company_id' => $this->company->id,
                'author_id' => $employee->id,
                'employee_id' => $employee->id,
                'timesheet_id' => $timesheet->id,
            ]);

            if (rand(1, 10) > 1) {
                (new ApproveTimesheet)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'timesheet_id' => $timesheet->id,
                ]);
            } else {
                (new RejectTimesheet)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'employee_id' => $employee->id,
                    'timesheet_id' => $timesheet->id,
                ]);
            }
        } else {
            (new SubmitTimesheet)->execute([
                'company_id' => $this->company->id,
                'author_id' => $employee->id,
                'employee_id' => $employee->id,
                'timesheet_id' => $timesheet->id,
            ]);
        }
    }

    private function setContractRenewalDates(): void
    {
        $this->info('☐ Add contract renewal dates for external employees');

        $date = Carbon::now()->addWeek();

        (new SetContractRenewalDate)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $this->glenn->id,
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ]);

        $date = Carbon::now()->addWeeks(2);

        (new SetContractRenewalDate)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'employee_id' => $this->meredith->id,
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
        ]);
    }

    private function setECoffeeProcess(): void
    {
        $this->info('☐ Set e Coffee Process');

        $this->company->e_coffee_enabled = true;
        $this->company->save();

        for ($i = 0; $i < 10; $i++) {
            (new MatchEmployeesForECoffee)->execute([
                'company_id' => $this->company->id,
            ]);
        }

        // mark random eCoffee matches as happened
        ECoffeeMatch::chunk(100, function ($matches) {
            $matches->each(function (ECoffeeMatch $match) {
                if (rand(1, 3) == 1) {
                    ECoffeeMatch::where('id', $match->id)->update([
                        'happened' => true,
                    ]);
                }
            });
        });
    }

    private function addGroups(): void
    {
        $this->info('☐ Add groups');

        $groupNames = collect([
            'Party planning committee',
            'Basketball lovers',
            'Monetisation executive meeting',
            'Front end developers guild',
        ]);

        $meetingAgendaItems = collect([
            'What should we do about the negociations with Home Depot?',
            'Discussion about strategic learnings',
            'Sales update: previous quarter’s results',
            'Team structure presentation',
            'Impact on shareholders',
            'iPhone 42 launch',
        ]);

        $decisionItems = collect([
            'Schedule meeting with supplier',
            'Angela to take responsability and pubicly apologize to Dwight',
            'Prepare forecasts for Q4',
            'Prepare UX for the future feature',
        ]);

        foreach ($groupNames as $name) {
            $randomEmployees = $this->employees->shuffle()->take(rand(4, 9))->pluck('id')->toArray();

            // create group with random employees
            $group = (new CreateGroup)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'name' => $name,
                'employees' => $randomEmployees,
            ]);

            $group->mission = 'This group was created to discuss all decisions we have to take together.';
            $group->save();

            // create meetings
            $date = Carbon::now()->subMonths(10);
            for ($i = 0; $i < rand(4, 9); $i++) {
                $meeting = (new CreateMeeting)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'group_id' => $group->id,
                ]);

                (new UpdateMeetingDate)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'group_id' => $group->id,
                    'meeting_id' => $meeting->id,
                    'date' => $date->addDays(rand(10, 57))->format('Y-m-d'),
                ]);

                // add agenda items
                foreach ($meetingAgendaItems as $item) {
                    $agendaItem = (new CreateAgendaItem)->execute([
                        'company_id' => $this->company->id,
                        'author_id' => $this->michael->id,
                        'group_id' => $group->id,
                        'meeting_id' => $meeting->id,
                        'summary' => $item,
                        'description' => null,
                        'presented_by_id' => $this->employees->shuffle()->first()->id,
                    ]);

                    $decisionItems = $decisionItems->shuffle()->take(rand(1, 3));
                    foreach ($decisionItems as $item) {
                        (new CreateMeetingDecision)->execute([
                            'company_id' => $this->company->id,
                            'author_id' => $this->michael->id,
                            'group_id' => $group->id,
                            'meeting_id' => $meeting->id,
                            'agenda_item_id' => $agendaItem->id,
                            'description' => $item,
                        ]);
                    }
                }
            }
        }
    }

    private function addPreviousPositionsHistory(): void
    {
        $this->info('☐ Add previous positions history');

        foreach ($this->employees as $employee) {
            $position = Position::inRandomOrder()->first();

            $started = Carbon::now()->subMonths(rand(24, 60));
            $ended = $started->copy()->addMonths(rand(12, 24));

            EmployeePositionHistory::create([
                'employee_id' => $employee->id,
                'position_id' => $position->id,
                'started_at' => $started,
                'ended_at' => $ended,
            ]);

            $position = Position::inRandomOrder()->first();
            $started = $ended->copy();
            $ended = $started->copy()->addMonths(rand(6, 12));

            EmployeePositionHistory::create([
                'employee_id' => $employee->id,
                'position_id' => $position->id,
                'started_at' => $started,
                'ended_at' => $ended,
            ]);
        }
    }

    private function addBillingAndInvoices(): void
    {
        $this->info('☐ Add past invoices');

        $maxNumberEmployees = Employee::count();

        $oneYearAgo = Carbon::now()->subYear();
        while (! $oneYearAgo->isSameMonth(Carbon::now())) {
            $randomNumberOfEmployees = Employee::inRandomOrder()->limit(rand(1, $maxNumberEmployees))->get();

            $usage = CompanyDailyUsageHistory::create([
                'company_id' => $this->company->id,
                'number_of_active_employees' => $randomNumberOfEmployees->count(),
                'created_at' => $oneYearAgo->format('Y-m-d'),
            ]);

            foreach ($randomNumberOfEmployees as $employee) {
                CompanyUsageHistoryDetails::create([
                    'usage_history_id' => $usage->id,
                    'employee_name' => $employee->name,
                    'employee_email' => $employee->email,
                ]);
            }

            // if the day is the end of month, create invoice
            if ($oneYearAgo->isSameDay($oneYearAgo->copy()->endOfMonth())) {
                // get highest usage of the month
                $usage = CompanyDailyUsageHistory::where('company_id', $this->company->id)
                    ->whereBetween('created_at', [
                        $oneYearAgo->copy()->startOfMonth(),
                        $oneYearAgo->copy()->endOfMonth(),
                    ])
                    ->orderBy('number_of_active_employees', 'desc')
                    ->first();

                CompanyInvoice::create([
                    'company_id' => $this->company->id,
                    'usage_history_id' => $usage->id,
                    'created_at' => $oneYearAgo->format('Y-m-d'),
                ]);
            }

            $oneYearAgo = $oneYearAgo->copy()->addDay();
        }
    }

    private function addWikis(): void
    {
        $this->info('☐ Add wikis and pages');

        $wikiNames = collect([
            'HR',
            'Development',
            'Product',
            'Finance',
            'Warehouse',
        ]);

        foreach ($wikiNames as $name) {
            $wiki = (new CreateWiki)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'title' => $name,
            ]);

            (new AddPageToWiki)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'wiki_id' => $wiki->id,
                'title' => 'Party planning committee',
                'content' => "
Historical members of the Party Planning Committee include former regular members Pam Beesly|Pam, Phyllis Vance|Phyllis (former chair), and Angela Martin|Angela (former chair). Other non-permanent members have once included Meredith Palmer|Meredith, Kelly Kapoor|Kelly, Karen Filippelli|Karen, Ryan Howard|Ryan, Holly Flax|Holly, and Oscar Martinez|Oscar. Jim Halpert|Jim and Dwight Schrute|Dwight were also temporary heads to prevent the chair from gaining too much power.

## Pre-history parties
These parties were planned prior to the beginning of the Office documentary.

### A Pizza of Your Own Party

* In a [http://www.hulu.com/watch/63883/the-office-party-planning-committee#s-p2-sr-i1 deleted scene] from `New Boss`, Michael Scott|Michael describes to Charles Miner the first party planned by the committee, which was a celebration of the release of the film ''A League of Their Own'' on Laserdisc. According to [http://www.imdb.com/title/tt0106055/laserdisc IMDB], the release of that film on Laserdisc occurred in 1993, making the committee 17 years old as of 2010. The celebration consisted of employees of the office making their own pizzas. (Gabe and Erin independently used the same idea for their ''Glee'' Viewing Party|viewing party.)

### The '80s Party

* In `The Alliance`, Michael Scott|Michael mentions a previous party; The '80s Party, which the office newsletter called `a success.`

## Angela Martin era parties
For the first four seasons, Angela Martin runs the Party Planning Committee.

### Meredith's Birthday Party
File:PartyPlanningCommittee1.jpg|thumb|left|250px|Angela, Pam, and Phyllis

* In `The Alliance`, the Party Planning Committee (Angela, Pam, and Phyllis) plan Meredith's surprise birthday party.

### 05 05 05

* In `The Dundies`, Jan mentions that Michael had a luau on May 5, 2005 for no reason. Michael refuted her vehemently, saying that it was `05 05 05` and `happens once every billion years.`

### Halloween Party

* In `Halloween`, the planned office Halloween party is a flop because Devon invites most of the office to Poor Richard's. The only attendees of the party are Michael Scott|Michael, Dwight Schrute|Dwight, Creed Bratton|Creed, and Angela.

### Christmas Party 2005

* In `Christmas Party`, the Party Planning Committee (Angela, Pam, Phyllis, Meredith, and new member Ryan) plan the office Christmas party. Michael gives Ryan all the credit, which infuriates Angela.

### Michael's Birthday Party

* The Party Planning Committee is credited for planning Michael's Birthday|Michael's birthday party, although Dwight assumes control of all planning duties exclaiming `This is the most important day of the year!`

### Christmas Party 2006

* In `A Benihana Christmas`, the Party Planning Committee (Angela, Pam, Phyllis, Meredith, and new member Karen) plan the office Christmas party. Pam and Karen break off from the Party Planning Committee to plan their own competing party as the newly dubbed The Committee to Plan Parties|Committee to Plan Parties.

### Luau Party

* In `Back From Vacation`, the Party Planning Committee (Angela, Pam, Phyllis, Meredith, and Karen) are ordered to throw a luau-themed party with only three hours' notice.

### Oscar's Welcome Back Party

* In `The Return`, the Party Planning Committee (Angela, Pam, Phyllis, Meredith, Karen, and new member Oscar) plan Oscar's `Welcome Back` party. When Dwight returns from STAPLES Michael tells him that the party is for him.

### Phyllis's Bridal Shower

* In `Ben Franklin`, Angela (presumably with the assistance of the Party Planning Committee) decorates the conference room for Phyllis's bridal shower.

### Dunder Mifflin Infinity Launch Party

* In `Launch Party`, the Party Planning Committee (Angela, Phyllis, Meredith, and an unnamed member, presumably Pam) plan the Dunder Mifflin Infinity launch party.

### Creed's Birthday/Birthday Month Party

* In `Survivor Man`, Michael leaves Jim Halpert|Jim in charge of the office. Jim attempts to merge Creed's, Oscar's, Meredith's, and Toby Flenderson|Toby's (even though his birthday was 3 months ago) birthday parties into just one party. The slew of requests, including peach cobbler for Creed, devil's food cake for Meredith, and Fudgie the Whale and mushroom caps for Andy Bernard|Andy make this party increasingly difficult for Angela to plan. The combined party concept doesn't go over well with the office workers, and in the end, they just celebrate Creed's birthday.

## Phyllis Vance era parties
While it is unclear if there was an official change in the leadership of the Party Planning Committee, Phyllis Vance becomes the source of party planning authority and the de facto, if not formal leader.

### Toby's Goodbye Party

* In `Goodbye, Toby`, Phyllis is given the task of planning Toby's goodbye party after Angela rejects Michael's demands as impossibly unrealistic. The party is a smashing success, which makes Angela even more upset.

### Weight Loss Party

* In `Weight Loss`, Phyllis has solidified control of the Party Planning Committee thanks to her knowledge of Angela and Dwight's secret affair. She tries to buy a cake for Stanley's birthday party to celebrate the success of the weight loss initiative, but Michael insists that it consist only of fruit. Phyllis secretly organizes a party with cheesecake but is found out.

### Baby Shower

* In `Baby Shower`, Phyllis organizes Jan's baby shower. Phyllis relishes her new role, although Michael is disappointed with the sparse party decorations and food.

### Christmas Party 2008

* In `Moroccan Christmas`, Phyllis organizes a Morocco-themed Christmas party. Phyllis orders Angela around to the point where Angela finally refuses to go along, confident that Phyllis is too meek to carry through on her threat. Her gambit backfires, however, when Phyllis reveals Angela and Dwight's affair to everyone in the office.

## Jim & Dwight era parties
Michael puts Jim and Dwight in charge of the Party Planning Committee because there was `too much drama.` Michael believes that consolidating power into a single head was too dangerous, so the committee chairmanship is now shared.

### Kelly's Missed Birthday

* In `Lecture Circuit Part 1|Lecture Circuit Parts 1 & 2`, Jim and Dwight forget Kelly's birthday and attempt to throw her a party the next day. It features sagging balloons held up with masking tape, a cake reading `Happy Birthday Kelley [sic]` and an `It is your birthday.` sign. They offer her the choice of watching TV for an hour or napping for an hour and Kelly gleefully decides to take a nap. Dwight wakes her up by banging two trash can lids together, and orders her to make up the time lost while she was napping.

### Michael's 15th Anniversary Party

* In `New Boss`, Jim, Dwight, and Pam meet with Michael to plan his 15th Anniversary Party. New Corporate Vice President Charles Miner shuts down the Party Planning Committee as a waste of time and money.

### Christmas Party 2009

* In `Secret Santa`, Jim and Dwight (resuming their duties as the Party Planning Committee) try to get the office in the Christmas spirit, but the uncertain future of Dunder Mifflin Paper Company|Dunder Mifflin puts a damper on the festivities. Jim's decision to let Phyllis Vance|Phyllis be Santa Claus infuriates Michael Scott|Michael.

## Second Angela Martin era parties

### Secretary's Day 2010

* In `Secretary's Day`, Andy thanks Angela for organizing the Secretary's Day party. Angela's return to the Party Planning Committee is not explained.

### Nellie's Welcome Party 2012

* Angela, Pam, Oscar and Phyllis work together to plan a terrible party for their new boss Nellie.

## Other notes

* In `Conflict Resolution`, it is revealed that Phyllis tried to get off the Party Planning Committee.

* Mentioned in the deleted scenes of `Launch Party`, Mentioned Characters#Denise Dimm|Denise Dimm is identified as the head of the Dunder Mifflin Buffalo|Buffalo branch's Party Planning Committee.

* In `Branch Wars`, Andy Bernard|Andy explains that his goal is to get into the office's most exclusive club - The Finer Things Club, with the Party Planning Committee as his backup and Scrantonicity|Kevin's band as his safety.

* In `Launch Party` Phyllis tries new techniques to deal with difficult people (e.g. Angela) off of Google while the Party Planning Committee is planning the Launch Party.

*  In `The New Boss`, Michael uses the code name PPC for the Party Planning Committee in an attempt to prevent Charles Miner from finding out about it.

*  In `Classy Christmas`, Pam mentions that, as office administrator, she is basically being paid to be the head of the Party Planning Committee.

*  Greg Daniels encouraged the actors to improvise mundane party planning chatter.<ref name=`officeladies_michaelsbirthday`>Kinsey, Angela and Jenna Fischer. 2020. [https://officeladies.com/episodes/2020/04/22/episode-25-michaels-birthday Episode 25: Michael's Birthday], Office Ladies podcast, April 22, 2020.</ref>
                ",
            ]);
        }
    }

    private function addRecruitingStages(): void
    {
        $this->info('☐ Add recruiting stages');

        $templates = collect([
            'Engineering flow',
            'Sales',
            'Marketing with a lot of experience',
        ]);

        $stages = collect([
            'Screening call',
            'Technical interview',
            'Interview with SM, PO',
            'Reference check',
            'Behavorial interview',
        ]);

        foreach ($templates as $template) {
            $template = (new CreateRecruitingStageTemplate)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'name' => $template,
            ]);

            $randomStages = $stages->shuffle()->take(rand(3, 6));
            foreach ($randomStages as $stage) {
                $stage = (new CreateRecruitingStage)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'recruiting_stage_template_id' => $template->id,
                    'name' => $stage,
                ]);
            }
        }
    }

    private function addJobOpenings(): void
    {
        $this->info('☐ Add job openings');

        $titles = collect([
            'Engineering manager',
            'Sales specialist',
            'Warehouse Worker With Forklift Experience',
        ]);

        for ($i = 0; $i < rand(4, 12); $i++) {
            foreach ($titles as $title) {
                // get random team
                $team = $this->teams->random();

                // get random sponsors
                $sponsors = $this->employees
                    ->take(rand(1, 2))
                    ->pluck('id')
                    ->toArray();

                // get random position
                $position = Position::get()->random();

                // get random recruiting stage
                $recruitingStageTemplate = RecruitingStageTemplate::get()->random();

                $opening = (new CreateJobOpening)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->michael->id,
                    'position_id' => $position->id,
                    'sponsors' => $sponsors,
                    'team_id' => $team->id,
                    'recruiting_stage_template_id' => $recruitingStageTemplate->id,
                    'title' => $title,
                    'reference_number' => $this->faker->text(5).'-'.$this->faker->randomNumber(),
                    'description' => $this->faker->realText(1000),
                ]);

                if (rand(1, 2) == 1) {
                    (new ToggleJobOpening)->execute([
                        'company_id' => $this->company->id,
                        'author_id' => $this->michael->id,
                        'job_opening_id' => $opening->id,
                    ]);
                }
            }
        }
    }

    private function addAMASessions(): void
    {
        $this->info('☐ Add Ask Me Anything sessions');

        $themes = collect([
            'New office',
            'New Year resolutions',
            'Launch of the new marketing website',
            'New plans for the next year',
            'Merge with the company we just bought',
        ]);

        $questions = collect([
            'Have you ever had a nickname? What is it?',
            'Do you like or dislike surprises? Why or why not?',
            'In the evening, would you rather play a game, visit a relative, watch a movie, or read?',
            'Would you rather vacation in Hawaii or Alaska, and why?',
            'Would you rather win the lottery or work at the perfect job? And why?',
            'Who would you want to be stranded with on a deserted island?',
            'If money was no object, what would you do all day?',
            'If you could go back in time, what year would you travel to?',
            'How would your friends describe you?',
            'What are your hobbies?',
            'What is the best gift you have been given?',
            'What is the worst gift you have received?',
            'Aside from necessities, what one thing could you not go a day without?',
            'List two pet peeves.',
            'Where do you see yourself in five years?',
            'How many pairs of shoes do you own?',
            'If you were a super-hero, what powers would you have?',
            'What would you do if you won the lottery?',
            'What form of public transportation do you prefer? (air, boat, train, bus, car, etc.)',
            'What is your favorite zoo animal?',
            'If you could go back in time to change one thing, what would it be?',
            'If you could share a meal with any 4 individuals, living or dead, who would they be?',
            'How many pillows do you sleep with?',
            'What is the longest you have gone without sleep (and why)?',
            'What is the tallest building you have been to the top in?',
        ]);

        $daysToSubtract = rand(200, 365);
        foreach ($themes as $theme) {
            // get random date
            $date = Carbon::now()->copy()->subDays($daysToSubtract);

            $ama = (new CreateAskMeAnythingSession)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->michael->id,
                'theme' => $theme,
                'date' => $date->format('Y-m-d'),
            ]);

            foreach ($questions->take(rand(10, 24)) as $question) {
                (new CreateAskMeAnythingQuestion)->execute([
                    'company_id' => $this->company->id,
                    'author_id' => $this->employees->shuffle()->first()->id,
                    'ask_me_anything_session_id' => $ama->id,
                    'question' => $question,
                    'anonymous' => rand(0, 1) == 1,
                ]);
            }

            $daysToSubtract = $daysToSubtract + rand(200, 365);
        }

        (new ToggleAskMeAnythingSession)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->michael->id,
            'ask_me_anything_session_id' => AskMeAnythingSession::orderBy('id', 'desc')->first()->id,
        ]);
    }

    private function addSecondaryBlankAccount(): void
    {
        $this->info('☐ Create a blank account');

        $user = (new CreateAccount)->execute([
            'email' => 'blank@blank.com',
            'password' => 'blank123',
            'first_name' => 'Roger',
            'last_name' => 'Rabbit',
        ]);

        $this->company = (new CreateCompany)->execute([
            'author_id' => $user->id,
            'name' => 'ACME inc',
        ]);
    }

    private function validateUserAccounts(): void
    {
        DB::table('users')
            ->update(['email_verified_at' => Carbon::now()]);
    }

    private function artisan(string $message, string $command, array $arguments = []): void
    {
        $this->info($message);
        $this->callSilent($command, $arguments);
    }

    private function createAvatar(string $uuid): File
    {
        return File::create([
            'company_id' => $this->company->id,
            'uploader_employee_id' => $this->michael->id,
            'uploader_name' => $this->michael->name,
            'uuid' => $uuid,
            'name' => 'name',
            'original_url' => 'https://ucarecdn.com/'.$uuid.'/',
            'cdn_url' => 'https://ucarecdn.com/'.$uuid.'/',
            'mime_type' => 'image/webp',
            'size' => 11100,
            'type' => 'avatar',
        ]);
    }
}
