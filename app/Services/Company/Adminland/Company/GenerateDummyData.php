<?php

namespace App\Services\Company\Adminland\Company;

use Faker\Factory as Faker;
use App\Jobs\NotifyEmployee;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Jobs\Dummy\CreateDummyTeam;
use Illuminate\Support\Facades\Cache;
use App\Jobs\Dummy\CreateDummyPosition;
use App\Jobs\Dummy\AddDummyEmployeeToCompany;

class GenerateDummyData extends BaseService
{
    protected array $data;

    protected Company $company;

    protected Employee $author;

    protected $faker;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Generate dummy data for the given company.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $this->data = $data;

        $this->faker = Faker::create();

        $this->company = Company::find($data['company_id']);
        $this->author = Employee::find($data['author_id']);

        $this->createPositions();
        $this->createTeams();
        $this->createEmployees();

        // $this->createWorklogEntries();

        // $this->createFiveUsersWithoutTeam($data);

        // $this->createThreeTeamsWithEmployees($data);

        // $this->createWorklogEntries();

        // $this->createMoraleEntries();

        // $this->createWorkFromHomeEntries();

        // $this->createCompanyNewsEntries($data);

        // $this->createQuestions($data);

        // $this->createHardware($data);

        $this->company->has_dummy_data = true;
        $this->company->save();

        // NotifyEmployee::dispatch([
        //     'employee_id' => $data['author_id'],
        //     'action' => 'dummy_data_generated',
        //     'objects' => json_encode([
        //         'company_name' => $company->name,
        //     ]),
        // ])->onQueue('low');

        // // reset the cached object as it has changed
        // $cachedCompanyObject = 'cachedCompanyObject_'.$data['author_id'];
        // Cache::put($cachedCompanyObject, $company, now()->addMinutes(60));
    }

    private function createPositions(): void
    {
        $listOfPositions = [
            'Regional Manager',
            'Assistant to the Regional Manager',
            'Regional Director of Sales',
            'Sales Rep.',
            'Traveling Sales Representative',
            'Senior Accountant',
            'Head of Accounting',
            'Accountant',
            'H.R Rep',
            'Receptionist',
            'Customer Service Representative',
            'Supplier Relations Rep.',
            'Quality Assurance',
            'Warehouse Foreman',
            'Warehouse Staff',
        ];

        foreach ($listOfPositions as $position) {
            $request = [
                'company_id' => $this->company->id,
                'author_id' => $this->author->id,
                'title' => $position,
            ];

            CreateDummyPosition::dispatch($request);
        }
    }

    private function createTeams(): void
    {
        $listOfTeams = [
            'Management' => ' ',
            'Sales',
            'Accounting',
            'Human Resources',
            'Reception',
            'Product Oversight',
            'Warehouse',
        ];

        foreach ($listOfTeams as $team) {
            $request = [
                'company_id' => $this->company->id,
                'author_id' => $this->author->id,
                'name' => $team,
            ];

            CreateDummyTeam::dispatch($request);
        }
    }

    private function createEmployees(): void
    {
        $this->addEmployee('Michael', 'Scott', 'Regional Manager', 'Management', '1965-03-15', null, 'Management');
        $this->addEmployee('Dwight', 'Schrute', 'Assistant to the Regional Manager', 'Management', '1970-01-20', 'Scott', null);
        $this->addEmployee('Phyllis', 'Vance', 'Regional Director of Sales', 'Sales', '1965-07-10', 'Scott', 'Sales');
        $this->addEmployee('Jim', 'Halpert', 'Sales Rep.', 'Sales', '1978-10-01', 'Vance', null);
        $this->addEmployee('Kelly', 'Kapoor', 'Customer Service Representative', 'Product Oversight', '1980-02-05', 'Scott', 'Product Oversight');
        $this->addEmployee('Angela', 'Martin', 'Head of Accounting', 'Accounting', '1974-11-11', 'Scott', 'Accounting');
        $this->addEmployee('Oscar', 'Martinez', 'Senior Accountant', 'Accounting', '1972-03-08', 'Martin', null);
        $this->addEmployee('Dakota', 'Blank', 'Accountant', 'Accounting', null, 'Martin', null);
        $this->addEmployee('Toby', 'Flenderson', 'H.R Rep', 'Human Resources', '1963-02-22', 'Scott', 'Human Resources');
        $this->addEmployee('Kevin', 'Malone', 'Accountant', 'Accounting', '1968-06-01', 'Martin', null);
        $this->addEmployee('Erin', 'Hannon', 'Receptionist', 'Reception', '1986-05-01', 'Scott', null);
        $this->addEmployee('Pete', 'Miller', 'Customer Service Representative', 'Product Oversight', null, 'Kapoor', null);
        $this->addEmployee('Meredith', 'Palmer', 'Supplier Relations Rep.', 'Product Oversight', '1959-11-12', 'Kapoor', null);
        $this->addEmployee('Val', 'Johnson', 'Warehouse Foreman', 'Warehouse', null, 'Scott', 'Warehouse');
        $this->addEmployee('Nate', 'Blank', 'Warehouse Staff ', 'Warehouse', null, 'Johnson', null);
        $this->addEmployee('Glenn', 'Blank', 'Warehouse Staff ', 'Warehouse', null, 'Johnson', null);
        $this->addEmployee('Philip', 'Blank', 'Warehouse Staff ', 'Warehouse', null, 'Johnson', null);
    }

    private function addEmployee(string $firstname, string $lastname, string $position, string $team, string $date = null, string $managerName = null, string $teamLeadOf = null): void
    {
        $request = [
            'company_id' => $this->company->id,
            'author_id' => $this->author->id,
            'email' => $this->faker->safeEmail,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
            'position_name' => $position,
            'team_name' => $team,
            'birthdate' => $date,
            'manager_name' => $managerName,
            'leader_of_team_name' => $teamLeadOf,
        ];

        AddDummyEmployeeToCompany::dispatch($request);
    }
}
