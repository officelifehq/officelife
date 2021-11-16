<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;

class DashboardHRDisciplineCaseViewHelper
{
    /**
     * Get the information about all the discipline cases.
     *
     * @param Company $company
     * @return array|null
     */
    public static function index(Company $company): ?array
    {
        $openCases = DisciplineCase::where('company_id', $company->id)
            ->where('active', true)
            ->addSelect([
                'number_of_events' => DisciplineEvent::selectRaw('count(*)')
                    ->whereColumn('discipline_case_id', 'discipline_cases.id'),
            ])
            ->orderBy('created_at', 'desc')
            ->with('author')
            ->with('employee')
            ->get();

        $openCasesCollection = collect([]);
        foreach ($openCases as $openCase) {
            $openCasesCollection->push([
                'id' => $openCase->id,
                'number_of_events' => (int) $openCase->number_of_events,
                'author' => [
                    'id' => $openCase->author->id,
                    'name' => $openCase->author->name,
                    'avatar' => ImageHelper::getAvatar($openCase->author),
                    'position' => (! $openCase->author->position) ? null : $openCase->author->position->title,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $openCase->author,
                    ]),
                ],
                'employee' => [
                    'id' => $openCase->employee->id,
                    'name' => $openCase->employee->name,
                    'avatar' => ImageHelper::getAvatar($openCase->employee),
                    'position' => (! $openCase->employee->position) ? null : $openCase->employee->position->title,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $openCase->employee,
                    ]),
                ],
            ]);
        }

        $closedCasesCount = DisciplineCase::where('company_id', $company->id)
            ->where('active', false)
            ->count();

        return [
            'open_cases' => $openCasesCollection,
            'open_cases_count' => $openCasesCollection->count(),
            'closed_cases_count' => $closedCasesCount,
            'url' => [
                'open' => route('dashboard.hr.disciplinecase.index', [
                    'company' => $company,
                ]),
                'closed' => route('dashboard.hr.disciplinecase.index.closed', [
                    'company' => $company,
                ]),
                'search' => route('dashboard.hr.disciplinecase.search.employees', [
                    'company' => $company,
                ]),
            ],
        ];
    }

    /**
     * Returns the potential employees who can be assigned a case.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function potentialEmployees(Company $company, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('email', 'LIKE', '%' . $criteria . '%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $potentialEmployeesCollection = collect([]);
        foreach ($potentialEmployees as $potential) {
            $potentialEmployeesCollection->push([
                'id' => $potential->id,
                'name' => $potential->name,
                'avatar' => ImageHelper::getAvatar($potential, 64),
            ]);
        }

        return $potentialEmployeesCollection;
    }
}
