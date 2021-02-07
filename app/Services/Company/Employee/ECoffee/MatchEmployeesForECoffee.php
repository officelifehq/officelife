<?php

namespace App\Services\Company\Employee\ECoffee;

use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MatchEmployeesForECoffee extends BaseService
{
    private Company $company;

    private array $data;

    private ECoffee $eCoffee;

    private Collection $unmatchedEmployees;

    private Collection $firstHalfOfEmployees;

    private Collection $secondHalfOfEmployees;

    private Collection $matchedEmployees;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
        ];
    }

    /**
     * Match employees with others for the e-coffee process.
     * The difficulty here is to make the process extremely efficient, in order
     * to not hydrate a lot of models to accomplish one simple thing: match
     * employees. To do that, we'll execute one SQL query to extract employees,
     * and with the power of collections, we'll match them, and eventually,
     * we'll save all the associations in one update query - making it really
     * fast and efficient.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->getListOfEmployeesToMatch();
        $this->match();
        $this->createECoffeeSession();
        $this->merge();
        $this->save();
        $this->setECoffeeSessionActive();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);
        $this->company = Company::findOrFail($this->data['company_id']);
    }

    private function getListOfEmployeesToMatch(): void
    {
        $this->unmatchedEmployees = DB::table('employees')
            ->where('company_id', $this->company->id)
            ->where('locked', false)
            ->pluck('id');
    }

    private function match(): void
    {
        // we will shuffle the list of unmatched employees into two, so it will
        // be random
        $shuffled = $this->unmatchedEmployees->shuffle();

        // then we will check if the list is odd or even
        // then we will split the collection in two equal parts
        // and match them together
        $this->split($shuffled);

        if ($shuffled->count() % 2 != 0) {
            $this->fillMissingMatch();
        }
    }

    private function split(Collection $collection): void
    {
        $this->firstHalfOfEmployees = collect([]);
        $this->secondHalfOfEmployees = collect([]);

        $half = ceil($collection->count() / 2);
        $chunks = $collection->chunk(intval($half));

        $this->firstHalfOfEmployees = $chunks->first();
        $this->secondHalfOfEmployees = $chunks->last()->values(); // reset indexes
    }

    private function fillMissingMatch(): void
    {
        // the second collection of employees is odd
        // this means we are missing one employee to be matched with
        // we will take one random employee for the first collection and put
        // it in the second collection
        // we also need to make sure that we don’t take the last item of the
        // collection so it won’t be matched with the same entry in the merged collection
        $temp = $this->firstHalfOfEmployees->slice(0, -1);
        $randomEmployee = $temp->shuffle()->first();

        $this->secondHalfOfEmployees->push($randomEmployee);
    }

    private function createECoffeeSession(): void
    {
        $batchNumber = 0;

        // get latest ecoffee
        $latestECoffee = ECoffee::where('company_id', $this->data['company_id'])
            ->latest()
            ->first();

        if ($latestECoffee) {
            $batchNumber = intval($latestECoffee->batch_number);
        }

        $this->eCoffee = ECoffee::create([
            'company_id' => $this->data['company_id'],
            'batch_number' => $batchNumber + 1,
        ]);
    }

    private function merge(): void
    {
        $this->matchedEmployees = collect([]);

        foreach ($this->firstHalfOfEmployees as $index => $item) {
            $this->matchedEmployees->push([
                'employee_id' => $item,
                'with_employee_id' => $this->secondHalfOfEmployees[$index],
                'e_coffee_id' => $this->eCoffee->id,
            ]);
        }
    }

    private function save(): void
    {
        $sqlQuery = 'INSERT INTO e_coffee_matches (employee_id, with_employee_id, e_coffee_id) VALUES ';

        foreach ($this->matchedEmployees as $line) {
            $sqlQuery .= '('.$line['employee_id'].','.$line['with_employee_id'].','.$line['e_coffee_id'].'),';
        }

        $sqlQuery = substr($sqlQuery, 0, -1);
        $sqlQuery .= ';';

        DB::insert($sqlQuery);
    }

    private function setECoffeeSessionActive(): void
    {
        // we need to mark the new eCoffee session active and set the previous
        // ones inactive
        ECoffee::where('id', '!=', $this->eCoffee->id)
            ->where('company_id', $this->company->id)
            ->update([
                'active' => false,
            ]);

        ECoffee::where('id', $this->eCoffee->id)->update([
            'active' => true,
        ]);
    }
}
