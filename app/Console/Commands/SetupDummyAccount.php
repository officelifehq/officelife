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
use App\Services\User\CreateAccount;
use App\Models\Company\EmployeeStatus;
use App\Services\Company\Team\SetTeamLead;
use App\Services\Company\Adminland\Team\CreateTeam;
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
use App\Services\Company\Employee\EmployeeStatus\AssignEmployeeStatusToEmployee;

class SetupDummyAccount extends Command
{
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
        $this->createTeams();
        $this->createEmployees();
        $this->addSkills();
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
        $this->info('✓ Filling database with fake data');
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
        $this->employeeStatusFullTime = (new CreateEmployeeStatus)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Full time',
        ]);

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

        $this->info('✓ Creating 3 employee statuses');
    }

    private function createPositions(): void
    {
        $this->positionRegionalManager = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Regional Manager',
        ]);
        $this->positionAssistantToTheRegionalManager = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Assistant to the Regional Manager',
        ]);
        $this->positionRegionalDirectorOfSales = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Regional Director of Sales',
        ]);
        $this->positionSalesRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Sales Rep.',
        ]);
        $this->positionTravelingSalesRepresentative = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Traveling Sales Representative',
        ]);
        $this->positionSeniorAccountant = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Senior Accountant',
        ]);
        $this->positionHeadOfAccounting = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Head of Accounting',
        ]);
        $this->positionAccountant = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Accountant',
        ]);
        $this->positionHRRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'H.R Rep',
        ]);
        $this->positionReceptionist = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Receptionist',
        ]);
        $this->positionCustomerServiceRepresentative = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Customer Service Representative',
        ]);
        $this->positionSupplierRelationsRep = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Supplier Relations Rep.',
        ]);
        $this->positionQualityAssurance = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Quality Assurance',
        ]);
        $this->positionWarehouseForeman = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Warehouse Foreman',
        ]);
        $this->positionWarehouseStaff = (new CreatePosition)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'Warehouse Staff',
        ]);

        $this->info('✓ Creating positions');
    }

    private function createTeams(): void
    {
        $this->teamManagement = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Management',
        ]);
        $this->teamSales = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Sales',
        ]);
        $this->teamAccounting = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Accounting',
        ]);
        $this->teamHumanResources = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Human Resources',
        ]);
        $this->teamReception = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Reception',
        ]);
        $this->teamProductOversight = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Product Oversight',
        ]);
        $this->teamWarehouse = (new CreateTeam)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'name' => 'Warehouse',
        ]);

        $this->info('✓ Creating teams');
    }

    private function createEmployees(): void
    {
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

        $this->info('✓ Creating all the employees');
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
            }
        }
    }

    private function createQuestions(): void
    {
        $questions = [
            'What is your favorite animal?',
            'What is the best movie you have seen this year?',
            'Care to share your best restaurant in town?',
            'Do you have any personal goals that you would like to share with us this week?',
        ];

        foreach ($questions as $question) {
            $request = [
                'company_id' => $this->company->id,
                'author_id' => $this->author->id,
                'title' => $question,
                'active' => false,
            ];

            (new CreateQuestion)->execute($request);
        }

        (new CreateQuestion)->execute([
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'title' => 'What is the best TV show of this year so far?',
            'active' => true,
        ]);
    }

    private function artisan($message, $command, array $arguments = [])
    {
        $this->info($message);
        $this->callSilent($command, $arguments);
    }
}
