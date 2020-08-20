<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Faker\Factory as Faker;
use App\Models\Company\Team;
use App\Models\User\Pronoun;
use App\Models\Company\Company;
use Illuminate\Console\Command;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\Question;
use App\Services\User\CreateAccount;
use App\Models\Company\EmployeeStatus;
use App\Services\Company\Team\SetTeamLead;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Services\Company\Adminland\Team\CreateTeam;
use Symfony\Component\Console\Output\ConsoleOutput;
use App\Services\Company\Employee\Answer\CreateAnswer;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Services\Company\Adminland\Company\CreateCompany;
use App\Services\Company\Employee\Birthdate\SetBirthdate;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Adminland\Position\CreatePosition;
use App\Services\Company\Adminland\Question\CreateQuestion;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;
use App\Services\Company\Employee\Pronoun\AssignPronounToEmployee;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;
use App\Services\Company\Employee\Description\SetPersonalDescription;
use App\Services\Company\Adminland\EmployeeStatus\CreateEmployeeStatus;
use App\Services\Company\Employee\WorkFromHome\UpdateWorkFromHomeInformation;
use App\Services\Company\Employee\EmployeeStatus\AssignEmployeeStatusToEmployee;

class SetupDummyAccount extends Command
{
    protected ProgressBar $progress;
    protected Company $company;

    // All the employees
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

    // Questions
    protected Question $questionWhatIsYourFavoriteAnimal;
    protected Question $questionWhatIsTheBestMovieYouHaveSeenThisYear;
    protected Question $questionCareToShareYourBestRestaurantInTown;
    protected Question $questionWhatIsYourFavoriteBand;
    protected Question $questionWhatAreTheCurrentHighlightsOfThisYearForYou;
    protected Question $questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek;
    protected Question $questionWhatIsTheBestTVShowOfThisYearSoFar;

    protected $faker;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:dummyaccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare an account with fake data so users can play with it';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->start();
        $this->wipeAndMigrateDB();
        $this->createFirstUser();
        $this->createEmployeeStatuses();
        $this->createPositions();
        $this->createQuestions();
        $this->createTeams();
        $this->createEmployees();
        $this->addSkills();
        $this->addWorkFromHomeEntries();
        $this->addAnswers();
        $this->stop();
    }

    private function start(): void
    {
        if (! $this->confirm('Are you sure you want to proceed? This will delete ALL data in your environment.')) {
            return;
        }

        $this->faker = Faker::create();
        $this->pronounHeHim = Pronoun::where('label', 'he/him')->first();
        $this->pronounSheHer = Pronoun::where('label', 'she/her')->first();
        $this->pronounTheyThem = Pronoun::where('label', 'they/them')->first();
    }

    private function stop(): void
    {
        $this->line('');
        $this->line('-----------------------------');
        $this->line('|');
        $this->line('| Welcome to OfficeLife');
        $this->line('|');
        $this->line('-----------------------------');
        $this->info('| You can now sign in to your account:');
        $this->line('| username: admin@admin.com');
        $this->line('| password: admin');
        $this->line('| URL:      '.config('app.url'));
        $this->line('-----------------------------');

        $this->info('Setup is done. Have fun.');
    }

    private function wipeAndMigrateDB(): void
    {
        $this->artisan('✓ Performing migrations', 'migrate:fresh');
        $this->artisan('✓ Symlink the storage folder', 'storage:link');
    }

    private function createFirstUser(): void
    {
        $user = (new CreateAccount)->execute([
            'email' => 'admin@admin.com',
            'password' => 'admin',
            'first_name' => 'John',
            'last_name' => 'Rambo',
        ]);

        $this->company = (new CreateCompany)->execute([
            'author_id' => $user->id,
            'name' => 'Dunder Mifflin',
        ]);

        // grab the employee that was just created
        $this->author = Employee::first();

        $this->info('✓ Creating first user in the account');
    }

    private function createEmployeeStatuses(): void
    {
        $this->setProgressBar('Adding employee statuses', 3);

        $this->employeeStatusFullTime = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Full time',
        ]);
        $this->progressBar->advance();

        $this->employeeStatusPartTime = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Part time',
        ]);

        $this->employeeStatusConsultant = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Consultant',
        ]);

        $this->stopProgressBar();
    }

    private function createPositions(): void
    {
        $this->setProgressBar('Adding employee positions', 15);

        $this->positionRegionalManager = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Regional Manager',
        ]);
        $this->progressBar->advance();

        $this->positionAssistantToTheRegionalManager = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Assistant to the Regional Manager',
        ]);
        $this->progressBar->advance();

        $this->positionRegionalDirectorOfSales = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Regional Director of Sales',
        ]);
        $this->progressBar->advance();

        $this->positionSalesRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Sales Rep.',
        ]);
        $this->progressBar->advance();

        $this->positionTravelingSalesRepresentative = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Traveling Sales Representative',
        ]);
        $this->progressBar->advance();

        $this->positionSeniorAccountant = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Senior Accountant',
        ]);
        $this->progressBar->advance();

        $this->positionHeadOfAccounting = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Head of Accounting',
        ]);
        $this->progressBar->advance();

        $this->positionAccountant = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Accountant',
        ]);
        $this->progressBar->advance();

        $this->positionHRRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'H.R Rep',
        ]);
        $this->progressBar->advance();

        $this->positionReceptionist = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Receptionist',
        ]);
        $this->progressBar->advance();

        $this->positionCustomerServiceRepresentative = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Customer Service Representative',
        ]);
        $this->progressBar->advance();

        $this->positionSupplierRelationsRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Supplier Relations Rep.',
        ]);
        $this->progressBar->advance();

        $this->positionQualityAssurance = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Quality Assurance',
        ]);
        $this->progressBar->advance();

        $this->positionWarehouseForeman = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Warehouse Foreman',
        ]);
        $this->progressBar->advance();

        $this->positionWarehouseStaff = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Warehouse Staff',
        ]);
        $this->stopProgressBar();
    }

    private function createTeams(): void
    {
        $this->setProgressBar('Adding teams', 7);

        $this->teamManagement = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Management',
        ]);
        $this->progressBar->advance();

        $this->teamSales = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Sales',
        ]);
        $this->progressBar->advance();

        $this->teamAccounting = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Accounting',
        ]);
        $this->progressBar->advance();

        $this->teamHumanResources = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Human Resources',
        ]);
        $this->progressBar->advance();

        $this->teamReception = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Reception',
        ]);
        $this->progressBar->advance();

        $this->teamProductOversight = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Product Oversight',
        ]);
        $this->progressBar->advance();

        $this->teamWarehouse = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Warehouse',
        ]);
        $this->stopProgressBar();
    }

    private function createEmployees(): void
    {
        $this->setProgressBar('Adding employees', 17);

        $this->michael = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Michael',
            'last_name' => 'Scott',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'World best boss. Or so they say.';
        $this->addSpecificDataToEmployee($this->michael, $description, $this->pronounHeHim, $this->teamManagement, $this->employeeStatusFullTime, $this->positionRegionalManager, '1965-03-15', null, $this->teamManagement);
        $this->progressBar->advance();

        $this->dwight = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Dwight',
            'last_name' => 'Schrute',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'At day I’m the best assistant regional manager there is. At night, I’m a seasoned beet farmer and a host of a wonderful AirBnB.';
        $this->addSpecificDataToEmployee($this->dwight, $description, $this->pronounHeHim, $this->teamManagement, $this->employeeStatusFullTime, $this->positionAssistantToTheRegionalManager, '1970-01-20', $this->michael);
        $this->progressBar->advance();

        $this->phyllis = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Phyllis',
            'last_name' => 'Vance',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Wife of the wonderful Bob Vance, who has a great company about fridges - come visit his stores in Scranton.';
        $this->addSpecificDataToEmployee($this->phyllis, $description, $this->pronounSheHer, $this->teamSales, $this->employeeStatusFullTime, $this->positionRegionalDirectorOfSales, '1965-07-10', $this->michael, $this->teamSales);
        $this->progressBar->advance();

        $this->jim = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Jim',
            'last_name' => 'Halpert',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I dream to be one day as good as my mentor, friend and eternal nemesys Dwight. Also, I have a wife and 3 beautiful children.';
        $this->addSpecificDataToEmployee($this->jim, $description, $this->pronounHeHim, $this->teamSales, $this->employeeStatusFullTime, $this->positionSalesRep, '1978-10-01', $this->phyllis);
        $this->progressBar->advance();

        $this->kelly = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Kelly',
            'last_name' => 'Kapoor',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Did you know that Brad Pitt will be back with Jenifer Aniston in a few years? You’ve read it here first.';
        $this->addSpecificDataToEmployee($this->kelly, $description, $this->pronounSheHer, $this->teamProductOversight, $this->employeeStatusFullTime, $this->positionSalesRep, '1980-02-05', $this->michael, $this->teamProductOversight);
        $this->progressBar->advance();

        $this->angela = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Angela',
            'last_name' => 'Martin',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I love cats and I have 24 of them in my house. I also think the workplace should be all about integrity and office romance.';
        $this->addSpecificDataToEmployee($this->angela, $description, $this->pronounSheHer, $this->teamAccounting, $this->employeeStatusFullTime, $this->positionHeadOfAccounting, '1974-11-11', $this->michael, $this->teamAccounting);
        $this->progressBar->advance();

        $this->oscar = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Oscar',
            'last_name' => 'Martinez',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Bike lover, huge Trivial Pursuit enthusiast and avid book reader.';
        $this->addSpecificDataToEmployee($this->oscar, $description, $this->pronounTheyThem, $this->teamAccounting, $this->employeeStatusFullTime, $this->positionSeniorAccountant, '1972-03-08', $this->angela);
        $this->progressBar->advance();

        $this->dakota = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Dakota',
            'last_name' => 'Johnson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Life is what we do of it --- Ophrah, 2020';
        $this->addSpecificDataToEmployee($this->dakota, $description, $this->pronounSheHer, $this->teamAccounting, $this->employeeStatusFullTime, $this->positionAccountant, null, $this->angela);
        $this->progressBar->advance();

        $this->toby = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Toby',
            'last_name' => 'Flenderson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I have a daughter, Sasha, and a master’s degree in social work from Temple University.';
        $this->addSpecificDataToEmployee($this->toby, $description, $this->pronounTheyThem, $this->teamHumanResources, $this->employeeStatusFullTime, $this->positionHRRep, '1963-02-22', $this->michael, $this->teamHumanResources);
        $this->progressBar->advance();

        $this->kevin = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Kevin',
            'last_name' => 'Malone',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Former Poker world champion, 6x times in a row, then I lost everything.';
        $this->addSpecificDataToEmployee($this->kevin, $description, $this->pronounHeHim, $this->teamAccounting, $this->employeeStatusPartTime, $this->positionAccountant, '1968-06-01', $this->angela);
        $this->progressBar->advance();

        $this->erin = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Erin',
            'last_name' => 'Hannon',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I used to work at a Taco Bell Express, but I really prefer working at Dunder Mifflin.';
        $this->addSpecificDataToEmployee($this->erin, $description, $this->pronounSheHer, $this->teamReception, $this->employeeStatusFullTime, $this->positionReceptionist, '1986-05-01', $this->michael);
        $this->progressBar->advance();

        $this->pete = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Pete',
            'last_name' => 'Miller',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Lover of baseball, basketball, football and everything that ends with balls.';
        $this->addSpecificDataToEmployee($this->pete, $description, $this->pronounHeHim, $this->teamProductOversight, $this->employeeStatusPartTime, $this->positionCustomerServiceRepresentative, null, $this->kelly);
        $this->progressBar->advance();

        $this->meredith = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Meredith',
            'last_name' => 'Palmer',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I have an unfortunate habit of suffering various misadventures. I’ve contracted herpes, been hit by Michael’s car, had my pelvis broken, had my hair set on fire, caught head lice, and been bitten by a bat, a rat, and a raccoon, all on separate occasions, and had to get rabies post-exposure treatment';
        $this->addSpecificDataToEmployee($this->meredith, $description, $this->pronounSheHer, $this->teamProductOversight, $this->employeeStatusFullTime, $this->positionSupplierRelationsRep, '1959-11-12', $this->kelly);
        $this->progressBar->advance();

        $this->val = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Val',
            'last_name' => 'Johnson',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I don’t have anything interesting to say, as I’m a secondary character of the show.';
        $this->addSpecificDataToEmployee($this->val, $description, $this->pronounSheHer, $this->teamWarehouse, $this->employeeStatusFullTime, $this->positionWarehouseForeman, null, $this->michael, $this->teamWarehouse);
        $this->progressBar->advance();

        $this->nate = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Nate',
            'last_name' => 'Troyat',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'Michael Jordan was the best. Period.';
        $this->addSpecificDataToEmployee($this->nate, $description, $this->pronounHeHim, $this->teamWarehouse, $this->employeeStatusFullTime, $this->positionWarehouseStaff, null, $this->val);
        $this->progressBar->advance();

        $this->glenn = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Glenn',
            'last_name' => 'Scott',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I am Glenn, and I approve this message.';
        $this->addSpecificDataToEmployee($this->glenn, $description, $this->pronounHeHim, $this->teamWarehouse, $this->employeeStatusPartTime, $this->positionWarehouseStaff, null, $this->val);
        $this->progressBar->advance();

        $this->philip = (new AddEmployeeToCompany)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => 'Philip',
            'last_name' => 'Scott',
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
        ]);
        $description = 'I lived in Chicago most of my life and have 2 children.';
        $this->addSpecificDataToEmployee($this->philip, $description, $this->pronounHeHim, $this->teamWarehouse, $this->employeeStatusFullTime, $this->positionWarehouseStaff, null, $this->val);

        $this->stopProgressBar();
    }

    private function addSpecificDataToEmployee(Employee $employee, string $description, Pronoun $pronoun, Team $team, EmployeeStatus $status, Position $position, string $birthdate = null, Employee $manager = null, Team $leaderOfTeam = null): void
    {
        (new AddEmployeeToTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ]);

        (new AssignPronounToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'employee_id' => $employee->id,
            'pronoun_id' => $pronoun->id,
            'team_id' => $team->id,
        ]);

        (new AssignEmployeeStatusToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'employee_id' => $employee->id,
            'employee_status_id' => $status->id,
        ]);

        (new AssignPositionToEmployee)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'employee_id' => $employee->id,
            'position_id' => $position->id,
        ]);

        if ($birthdate) {
            $date = Carbon::parse($birthdate);
            (new SetBirthdate)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->author->id,
                'employee_id' => $employee->id,
                'year' => $date->year,
                'month' => $date->month,
                'day' => $date->day,
            ]);
        }

        if ($manager) {
            (new AssignManager)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->author->id,
                'employee_id' => $employee->id,
                'manager_id' => $manager->id,
            ]);
        }

        if ($leaderOfTeam) {
            (new SetTeamLead)->execute([
                'company_id' => $this->company->id,
                'author_id' => $this->author->id,
                'employee_id' => $employee->id,
                'team_id' => $leaderOfTeam->id,
            ]);
        }

        (new SetPersonalDescription)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'employee_id' => $employee->id,
            'description' => $description,
        ]);
    }

    private function addSkills(): void
    {
        $this->setProgressBar('Assigning skills to employees', 50);

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
                    'author_id' => $this->author->id,
                    'employee_id' => $skill['employee'],
                    'name' => $individualSkill,
                ]);
                $this->progressBar->advance();
            }
        }

        $this->stopProgressBar();
    }

    private function addWorkFromHomeEntries(): void
    {
        $employees = Employee::all();
        $this->setProgressBar('Adding work from home entries', $employees->count());
        foreach ($employees as $employee) {
            // 50% chances of having work from home entries
            if (rand(1, 2) != 1) {
                $this->progressBar->advance();
                continue;
            }

            $twoYearsAgo = Carbon::now()->subYears(2);
            while (! $twoYearsAgo->isSameDay(Carbon::now())) {
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
                    'author_id' => $this->author->id,
                    'employee_id' => $employee->id,
                    'date' => $twoYearsAgo->format('Y-m-d'),
                    'work_from_home' => true,
                ]);

                $twoYearsAgo->addDay();
            }
            $this->progressBar->advance();
        }

        $this->stopProgressBar();
    }

    private function createQuestions(): void
    {
        $this->setProgressBar('Adding questions in the company', 7);

        $this->questionWhatIsYourFavoriteAnimal = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => false,
            'title' => 'What is your favorite animal?',
        ]);
        $this->progressBar->advance();

        $this->questionWhatIsTheBestMovieYouHaveSeenThisYear = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => false,
            'title' => 'What is the best movie you have seen this year?',
        ]);
        $this->progressBar->advance();

        $this->questionCareToShareYourBestRestaurantInTown = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => false,
            'title' => 'Care to share your best restaurant in town?',
        ]);
        $this->progressBar->advance();

        $this->questionWhatIsYourFavoriteBand = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => false,
            'title' => 'What is your favorite band?',
        ]);
        $this->progressBar->advance();

        $this->questionWhatAreTheCurrentHighlightsOfThisYearForYou = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => false,
            'title' => 'What are the current highlights of this year for you?',
        ]);
        $this->progressBar->advance();

        $this->questionDoYouHaveAnyPersonalGoalsThatYouWouldLikeToShareWithUsThisWeek = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => false,
            'title' => 'Do you have any personal goals that you would like to share with us this week?',
        ]);
        $this->progressBar->advance();

        $this->questionWhatIsTheBestTVShowOfThisYearSoFar = (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'active' => true,
            'title' => 'What is the best TV show of this year so far?',
        ]);

        $this->stopProgressBar();
    }

    private function addAnswers(): void
    {
        $this->setProgressBar('Adding answers to questions', 89);
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->author, 'I love cats and dogs equally, but really, I prefer dogs as cats just want to murder us.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->michael, 'The lion, magnificent and powerful like me.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->dwight, 'The best animal is the one that tastes best on my plate.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->phyllis, 'I love dolphins as they are beautiful, graceful and nice to other animals - except sharks.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->jim, 'Dogs. Friendly, nice, with only one master, like Dwight.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->kelly, 'What’s the favorite animal of Brad Pitt?');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->angela, 'Cats. The more, the better.');
        $this->writeAnswer($this->questionWhatIsYourFavoriteAnimal, $this->oscar, 'I love dogs. They are friendly and nice.');

        $this->writeAnswer($this->questionWhatIsTheBestMovieYouHaveSeenThisYear, $this->author, 'Definitely the Peter Jackson movie, The Hobbit, which was amazing and superbly written.');
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

        $this->writeAnswer($this->questionWhatIsTheBestTVShowOfThisYearSoFar, $this->michael, 'Dawson Creek');
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
        $this->stopProgressBar();
    }

    private function writeAnswer(Question $question, Employee $employee, string $answer): void
    {
        (new CreateAnswer)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'employee_id' => $employee->id,
            'question_id' => $question->id,
            'body' => $answer,
        ]);
        $this->progressBar->advance();
    }

    private function artisan($message, $command, array $arguments = [])
    {
        $this->info($message);
        $this->callSilent($command, $arguments);
    }

    private function setProgressBar(string $message, int $max): void
    {
        $output = new ConsoleOutput();
        ProgressBar::setFormatDefinition('custom', '%message% (%current%/%max%) -- <info>%elapsed%</info>');
        $this->progressBar = new ProgressBar($output, $max);
        $this->progressBar->setFormat('custom');
        $this->progressBar->setMessage($message);
        $this->progressBar->start();
    }

    private function stopProgressBar(): void
    {
        $this->progressBar->finish();
        $this->line('');
    }
}
